<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\KategoriModel;
use App\Models\PenggunaModel;
use App\Models\TingkatPrestasiModel;
use App\Models\PeriodeModel;
use App\Models\RoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LombaController extends Controller
{
    public function indexAdmin()
    {
        $activeMenu = 'lomba';
        $breadcrumb = (object)[
            'title' => 'Manajemen Lomba',
            'list' => [' Manajemen Lomba']
        ];

        return view('lomba.indexAdmin', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
        ]);
    }

    public function list(Request $request)
    {
        $lomba = LombaModel::with(['kategori', 'tingkatPrestasi'])->get();

        return DataTables::of($lomba)
            ->addIndexColumn()
            ->addColumn('kategori', fn($l) => $l->kategori->nama_kategori ?? '-')
            ->addColumn('tingkat', fn($l) => $l->tingkatPrestasi->nama_tingkat_prestasi ?? '-')
            ->addColumn('aksi', function ($l) {
                $btn = '<a href="' . url('/lomba/' . $l->id_lomba . '/show') . '" class="btn btn-info btn-sm">Detail</a>';
                $btn .= '<button onclick="modalAction(\'' . url('/lomba/' . $l->id_lomba . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/lomba/' . $l->id_lomba . '/confirm_delete') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show($id)
{
    $data = LombaModel::with(['kategori', 'tingkatPrestasi', 'pengusul','periode'])->find($id);
    $activeMenu = 'lomba';
        $breadcrumb = (object)[
            'title' => 'Detail Lomba',
            'list' => ['Detail Lomba']
        ];
    return view('lomba.show', compact('data', 'breadcrumb'));
}

public function setujui($id_lomba)
{
    $lomba = LombaModel::findOrFail($id_lomba);
    $lomba->status_verifikasi = 1;
    $lomba->save();

    return redirect()->back()->with('success', 'Lomba berhasil disetujui!');
}

public function tolak($id_lomba)
{
    $lomba = LombaModel::findOrFail($id_lomba);
    $lomba->status_verifikasi = 0;
    $lomba->save();

    return redirect()->back()->with('success', 'Lomba berhasil ditolak!');
}

    public function create()
    {
        return view('lomba.create', [
            'id_kategori' => KategoriModel::all(),
            'id_tingkat_prestasi' => TingkatPrestasiModel::all(),
            'periode' => PeriodeModel::all(),
            'added_by'=> PenggunaModel::all(),
            'role_pengusul' => RoleModel::all()
        ]);
    }

public function store(Request $request)
{
    $rules = [
        'nama_lomba' => 'required|string|max:255',
        'penyelenggara' => 'required|string|max:255',
        'id_kategori' => 'required|exists:kategori,id_kategori',
        'id_tingkat_prestasi' => 'required|exists:tingkat_prestasi,id_tingkat_prestasi',
        'deskripsi' => 'required|string',
        'periode' => 'required|integer',
        'link_pendaftaran' => 'required|url',
        'biaya_pendaftaran' => 'required|boolean',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'deadline_pendaftaran' => 'required|date|before_or_equal:tanggal_mulai',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validasi gagal.',
            'msgField' => $validator->errors()
        ]);
    }

    if (!auth()->check()) {
        return response()->json([
            'status' => false,
            'message' => 'Pengguna tidak terautentikasi.'
        ]);
    }

    $data = $request->except('foto'); // exclude foto dulu

    // Simpan foto jika ada
    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $path = $file->store('image/foto_lomba', 'public');
        $data['foto'] = $path;
    }

    // Data tambahan
    $data['added_by'] = auth()->user()->id_pengguna;
    $data['role_pengusul'] = auth()->user()->role_id;
    $data['status_verifikasi'] = auth()->user()->role_id == 1 ? true : null; // Admin langsung verifikasi

    LombaModel::create($data);

    return response()->json([
        'status' => true,
        'message' => 'Lomba berhasil disimpan.'
    ]);
}

public function edit($id_lomba)
{
    $lomba = LombaModel::with(['kategori', 'tingkatPrestasi', 'periode'])->find($id_lomba);

    if (!$lomba) {
        return response()->json([
            'status' => false,
            'message' => 'Data Lomba tidak ditemukan.'
        ]);
    }

    if ($lomba->status_verifikasi == 1) {
        if (request()->ajax()) {
            return response()->json([
                'status' => false,
                'message' => 'Lomba sudah diverifikasi, tidak dapat diedit.'
            ]);
        }

       
        return redirect()->back()->with('error', 'Lomba sudah diverifikasi, tidak dapat diedit.');
    }

    $id_kategori = KategoriModel::all();
    $id_tingkat_prestasi = TingkatPrestasiModel::all();
    $id_periode = PeriodeModel::all();
    
    
    return view('lomba.edit', compact('lomba', 'id_kategori', 'id_tingkat_prestasi', 'id_periode'));
}

public function update(Request $request, $id)
{
    $lomba = LombaModel::find($id);

    if (!$lomba) {
        return response()->json([
            'status' => false,
            'message' => 'Data lomba tidak ditemukan.'
        ]);
    }

    if ($lomba->status_verifikasi == 1) {
        return response()->json([
            'status' => false,
            'message' => 'Lomba sudah diverifikasi dan tidak bisa diedit.'
        ]);
    }

    $validator = Validator::make($request->all(), [
        'nama_lomba' => 'required|string|max:255',
        'penyelenggara' => 'required|string|max:255',
        'id_kategori' => 'required|exists:kategori,id_kategori',
        'id_tingkat_prestasi' => 'required|exists:tingkat_prestasi,id_tingkat_prestasi',
        'deskripsi' => 'required|string',
        'periode' => 'required|exists:periode,id_periode',
        'link_pendaftaran' => 'url',
        'biaya_pendaftaran' => 'required|in:0,1',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'deadline_pendaftaran' => 'required|date|before_or_equal:tanggal_mulai',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }

    // Proses update data
    $lomba->nama_lomba = $request->nama_lomba;
    $lomba->penyelenggara = $request->penyelenggara;
    $lomba->id_kategori = $request->id_kategori;
    $lomba->id_tingkat_prestasi = $request->id_tingkat_prestasi;
    $lomba->deskripsi = $request->deskripsi;
    $lomba->periode = $request->periode;
    $lomba->link_pendaftaran = $request->link_pendaftaran;
    $lomba->biaya_pendaftaran = $request->biaya_pendaftaran;
    $lomba->tanggal_mulai = $request->tanggal_mulai;
    $lomba->tanggal_selesai = $request->tanggal_selesai;
    $lomba->deadline_pendaftaran = $request->deadline_pendaftaran;

    // Handle upload foto jika ada
    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('public/foto_lomba', $filename);
        $lomba->foto = $filename;
    }

    $lomba->save();

    return response()->json([
        'status' => true,
        'message' => 'Data lomba berhasil diperbarui.'
    ]);
}

    public function confirm_delete($id)
    {
        $data = LombaModel::with(['kategori', 'tingkatPrestasi', 'periode'])->findOrFail($id);
        return view('lomba.delete', ['data' => $data]);
    }

    public function delete(Request $request, $id)
    {
        $lomba = LombaModel::find($id);
        if ($lomba) {
            $lomba->delete();
            return response()->json([
                'status' => true,
                'message' => 'Lomba berhasil dihapus.'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Lomba tidak ditemukan.'
        ]);
    }
}