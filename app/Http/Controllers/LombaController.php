<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\KategoriModel;
use App\Models\PenggunaModel;
use App\Models\TingkatPrestasiModel;
use App\Models\PeriodeModel;
use App\Models\RoleModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
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

        $kategori = KategoriModel::all();

        return view('lomba.indexAdmin', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori,
        ]);
    }


    public function indexMahasiswa()
    {
        $activeMenu = 'lomba';
        $breadcrumb = (object)[
            'title' => 'Lomba',
            'list' => ['Lomba']
        ];

        $kategori = KategoriModel::all();

        return view('lomba.indexMahasiswa', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori,
        ]);
    }

    public function inputLomba()
    {
        $activeMenu = 'lomba';
        $breadcrumb = (object)[
            'title' => 'Input Lomba',
            'list' => [' Input Lomba']
        ];

        $kategori = KategoriModel::all();

        return view('lomba.inputLomba', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori,
        ]);
    }

    public function listAdmin(Request $request)
    {
        $keyword = $request->input('keyword');
        $filterKategori = $request->input('kategori');
        $statusVerifikasi = $request->input('status_verifikasi');

        $lomba = LombaModel::with('kategori')
            ->when($filterKategori, function ($query, $filterKategori) {
                return $query->where('id_kategori', $filterKategori);
            })
            ->when($keyword, function ($query, $keyword) {
                return $query->where('nama_lomba', 'like', "%{$keyword}%");
            })
            ->when($statusVerifikasi === 'belum', function ($query) {
                return $query->whereNull('status_verifikasi');
            })
            ->when($statusVerifikasi === 'sudah', function ($query) {
                return $query->whereIn('status_verifikasi', [0, 1]);
            })
            ->get();

        // Buat return sesuai kebutuhan front end
        $data = $lomba->map(function ($item) {
            return [
                'id_lomba' => $item->id_lomba,
                'nama_lomba' => $item->nama_lomba,
                'nama_kategori' => $item->kategori->nama_kategori ?? '-',
                'deskripsi' => $item->deskripsi,
                'link_pendaftaran' => $item->link_pendaftaran,
                'foto' => $item->foto,
                'status_verifikasi' => $item->status_verifikasi,
                'aksi' => auth()->user()->role_id == 1
                    ? '<a href="' . url('/lomba/' . $item->id_lomba . '/show') . '" class="btn btn-info btn-sm">Detail</a> ' .
                    '<button onclick="modalAction(\'' . url('/lomba/' . $item->id_lomba . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ' .
                    '<button onclick="modalAction(\'' . url('/lomba/' . $item->id_lomba . '/confirm_delete') . '\')" class="btn btn-danger btn-sm">Hapus</button>'
                    : '<a href="' . url('/lomba/' . $item->id_lomba . '/showMahasiswa') . '" class="btn btn-info btn-sm">Detail</a>',
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function listLomba(Request $request)
    {
        $keyword = $request->input('keyword');
        $filterKategori = $request->input('kategori');
        $statusVerifikasi = $request->input('status_verifikasi');

        $lomba = LombaModel::with('kategori')
            ->where('status_verifikasi', 1) // hanya lomba yang disetujui
            ->whereDate('deadline_pendaftaran', '>=', Carbon::today()) // tambahkan ini
            ->when($filterKategori, function ($query, $filterKategori) {
                return $query->where('id_kategori', $filterKategori);
            })
            ->when($keyword, function ($query, $keyword) {
                return $query->where('nama_lomba', 'like', "%{$keyword}%");
            })
            ->when($statusVerifikasi === 'belum', function ($query) {
                return $query->whereNull('status_verifikasi');
            })
            ->when($statusVerifikasi === 'sudah', function ($query) {
                return $query->whereIn('status_verifikasi', [0, 1]);
            })
            ->get();

        $data = $lomba->map(function ($item) {
            return [
                'id_lomba' => $item->id_lomba,
                'nama_lomba' => $item->nama_lomba,
                'nama_kategori' => $item->kategori->nama_kategori ?? '-',
                'deskripsi' => $item->deskripsi,
                'link_pendaftaran' => $item->link_pendaftaran,
                'foto' => $item->foto,
                'status_verifikasi' => $item->status_verifikasi,
                'aksi' => auth()->user()->role_id == 1
                    ? '<a href="' . url('/lomba/' . $item->id_lomba . '/show') . '" class="btn btn-info btn-sm">Detail</a> ' .
                    '<button onclick="modalAction(\'' . url('/lomba/' . $item->id_lomba . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ' .
                    '<button onclick="modalAction(\'' . url('/lomba/' . $item->id_lomba . '/confirm_delete') . '\')" class="btn btn-danger btn-sm">Hapus</button>'
                    : '<a href="' . url('/lomba/' . $item->id_lomba . '/showMahasiswa') . '" class="btn btn-info btn-sm">Detail</a>',
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function listInput(Request $request)
    {
        $user = auth()->user();
        $keyword = $request->input('keyword');
        $filterKategori = $request->input('kategori');
        $filterStatus = $request->input('status_verifikasi'); // Tambahan

        $lomba = LombaModel::with(['kategori', 'tingkatPrestasi'])
            ->where('added_by', $user->id_pengguna)
            ->when($filterKategori, function ($query, $filterKategori) {
                return $query->where('id_kategori', $filterKategori);
            })
            ->when($filterStatus !== null, function ($query) use ($filterStatus) {
                return $query->where('status_verifikasi', $filterStatus);
            })
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function ($q) use ($keyword) {
                    $q->where('nama_lomba', 'like', "%{$keyword}%")
                        ->orWhereHas('kategori', fn($q2) => $q2->where('nama_kategori', 'like', "%{$keyword}%"))
                        ->orWhereHas('tingkatPrestasi', fn($q3) => $q3->where('nama_tingkat_prestasi', 'like', "%{$keyword}%"));
                });
            });

        return DataTables::of($lomba->get())
            ->addIndexColumn()
            ->addColumn('kategori', fn($l) => $l->kategori->nama_kategori ?? '-')
            ->addColumn('nama_kategori', fn($l) => $l->kategori->nama_kategori ?? '-')
            ->addColumn('tingkat', fn($l) => $l->tingkatPrestasi->nama_tingkat_prestasi ?? '-')
            ->addColumn('aksi', function ($l) use ($user) {
                $btn = '<a href="' . url('/lomba/' . $l->id_lomba . '/showInput') . '" class="btn btn-info btn-sm">Detail</a>';
                $btn .= '<button onclick="modalAction(\'' . url('/lomba/' . $l->id_lomba . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/lomba/' . $l->id_lomba . '/confirm_delete') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show($id)
    {
        $data = LombaModel::with(['kategori', 'tingkatPrestasi', 'pengusul', 'periode'])->find($id);
        $activeMenu = 'lomba';
        $breadcrumb = (object)[
            'title' => 'Detail Lomba',
            'list' => ['Detail Lomba']
        ];
        return view('lomba.show', compact('data', 'breadcrumb'));
    }

    public function showMahasiswa($id)
    {
        $data = LombaModel::with(['kategori', 'tingkatPrestasi', 'pengusul', 'periode'])->find($id);
        $activeMenu = 'lomba';
        $breadcrumb = (object)[
            'title' => 'Detail Lomba',
            'list' => ['Detail Lomba']
        ];
        return view('lomba.showMahasiswa', compact('data', 'breadcrumb'));
    }

    public function showInput($id)
    {
        $data = LombaModel::with(['kategori', 'tingkatPrestasi', 'pengusul', 'periode'])->find($id);
        $activeMenu = 'lomba';
        $breadcrumb = (object)[
            'title' => 'Detail Lomba',
            'list' => ['Detail Lomba']
        ];
        return view('lomba.showInput', compact('data', 'breadcrumb'));
    }

    public function setujui($id_lomba)
    {
        $lomba = LombaModel::findOrFail($id_lomba);
        $lomba->status_verifikasi = 1;
        $lomba->save();

        return redirect()->back()->with('success', 'Data Lomba berhasil disetujui!');
    }

    public function tolak($id_lomba)
    {
        $lomba = LombaModel::findOrFail($id_lomba);
        $lomba->status_verifikasi = 0;
        $lomba->save();

        return redirect()->back()->with('success', 'Data Lomba berhasil ditolak!');
    }

    public function create()
    {
        return view('lomba.create', [
            'id_kategori' => KategoriModel::all(),
            'id_tingkat_prestasi' => TingkatPrestasiModel::all(),
            'periode' => PeriodeModel::all(),
            'added_by' => PenggunaModel::all(),
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
            'berhadiah' => 'required|boolean',
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

        $data = $request->only([
            'nama_lomba',
            'penyelenggara',
            'id_kategori',
            'id_tingkat_prestasi',
            'deskripsi',
            'periode',
            'link_pendaftaran',
            'biaya_pendaftaran',
            'berhadiah',
            'tanggal_mulai',
            'tanggal_selesai',
            'deadline_pendaftaran',
        ]);

        // Simpan foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('foto_lomba', $filename, 'public');
            $data['foto'] = 'foto_lomba/' . $filename;
        }

        // Data tambahan
        $data['added_by'] = auth()->user()->id_pengguna;
        $data['role_pengusul'] = auth()->user()->role_id;
        $data['status_verifikasi'] = auth()->user()->role_id == 1 ? true : null;

        
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
            'berhadiah' => 'required|in:0,1',
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

        // Update data lomba dari request
        $lomba->nama_lomba = $request->nama_lomba;
        $lomba->penyelenggara = $request->penyelenggara;
        $lomba->id_kategori = $request->id_kategori;
        $lomba->id_tingkat_prestasi = $request->id_tingkat_prestasi;
        $lomba->deskripsi = $request->deskripsi;
        $lomba->periode = $request->periode;
        $lomba->link_pendaftaran = $request->link_pendaftaran;
        $lomba->biaya_pendaftaran = $request->biaya_pendaftaran;
        $lomba->berhadiah = $request->berhadiah;
        $lomba->tanggal_mulai = $request->tanggal_mulai;
        $lomba->tanggal_selesai = $request->tanggal_selesai;
        $lomba->deadline_pendaftaran = $request->deadline_pendaftaran;

        // Handle upload foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Hapus foto lama jika ada
            if ($lomba->foto && Storage::disk('public')->exists($lomba->foto)) {
                Storage::disk('public')->delete($lomba->foto);
            }

            // Simpan foto baru
            $file->storeAs('foto_lomba', $filename, 'public');

            // Simpan path relatif ke database
            $lomba->foto = 'foto_lomba/' . $filename;
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