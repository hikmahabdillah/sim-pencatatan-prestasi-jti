<?php

namespace App\Http\Controllers;

use App\Models\TingkatPrestasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TingkatPrestasiController extends Controller
{
    public function index()
    {
        $activeMenu = 'tingkat_prestasi'; // digunakan untuk menandai menu aktif di sidebar
        $breadcrumb = (object)[
            'title' => 'Manajeman Tingkat Prestasi', // untuk title halaman
            'list'  => ['Tingkat Prestasi'] // untuk breadcrumb
        ];

        return view('tingkat_prestasi.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
        ]);
    }

    // fungsi untuk menampilkan data ke dalam DataTable
    public function list(Request $request)
    {
        $tingkatprestasi = TingkatPrestasiModel::all();

        return DataTables::of($tingkatprestasi)
            ->addIndexColumn()
            ->addColumn('aksi', function ($tingkatprestasi) {
                $btn  = '<button onclick="modalAction(\'' . url('/tingkat_prestasi/' . $tingkatprestasi->id_tingkat_prestasi . '/show') . '\')" class="btn btn-info btn-sm" >Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tingkat_prestasi/' . $tingkatprestasi->id_tingkat_prestasi . '/edit') . '\')" class="btn btn-warning btn-sm" >Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tingkat_prestasi/' . $tingkatprestasi->id_tingkat_prestasi . '/confirm_delete') . '\')" class="btn btn-danger btn-sm" >Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // fungsi untuk menampilkan detail data
    public function show(string $id)
    {
        $datatingkatprestasi = TingkatPrestasiModel::find($id);
        return view('tingkat_prestasi.show', ['data' => $datatingkatprestasi]);
    }

    // fungsi untuk menampilkan form create
    public function create()
    {
        return view('tingkat_prestasi.create');
    }

    // fungsi untuk menyimpan data ke dalam database
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_tingkat_prestasi' => 'required|unique:tingkat_prestasi|string|max:15',
            ];

            $validator = Validator::make($request->all(), [
                'created_at' => now(),
                'updated_at' => now(),
            ], $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ], 422);
            }

            TingkatPrestasiModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $request->all(),
            ]);
        }
        return redirect('/');
        // dd($request->all());
    }

    // fungsi untuk menampilkan form edit
    public function edit(string $id)
    {
        $data = TingkatPrestasiModel::find($id);
        return view('tingkat_prestasi.edit', ['data' => $data]);
    }

    // fungsi untuk mengupdate data ke dalam database
    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_tingkat_prestasi' => 'required|unique:tingkat_prestasi|string|max:15',
            ];

            $validator = Validator::make($request->all(), ['updated_at' => now(),], $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ], 422);
            }

            $data = TingkatPrestasiModel::find($id);
            if ($data) {
                $data->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    // fungsi untuk menampilkan konfirmasi hapus
    public function confirm_delete(string $id)
    {
        $data = TingkatPrestasiModel::find($id);
        return view('tingkat_prestasi.delete', ['data' => $data]);
    }

    // fungsi untuk menghapus data dari database
    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kategori = TingkatPrestasiModel::find($id);
            if ($kategori) {
                $kategori->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }
}
