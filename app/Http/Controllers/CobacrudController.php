<?php

namespace App\Http\Controllers;

use App\Models\CobacrudModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CobacrudController extends Controller
{
    public function index()
    {
        $activeMenu = 'cobacrud'; // digunakan untuk menandai menu aktif di sidebar
        $breadcrumb = (object)[
            'title' => 'Coba CRUD', // untuk title halaman
            'list'  => ['Coba CRUD'] // untuk breadcrumb
        ];

        return view('cobacrud.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
        ]);
    }

    // fungsi untuk menampilkan data ke dalam DataTable
    public function list(Request $request)
    {
        $cobaCrud = CobacrudModel::all();

        return DataTables::of($cobaCrud)
            ->addIndexColumn()
            ->addColumn('aksi', function ($cobacrud) {
                $btn  = '<button onclick="modalAction(\'' . url('/cobacrud/' . $cobacrud->id_kategori . '/show') . '\')" class="btn btn-info btn-sm" >Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/cobacrud/' . $cobacrud->id_kategori . '/edit') . '\')" class="btn btn-warning btn-sm" >Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/cobacrud/' . $cobacrud->id_kategori . '/confirm_delete') . '\')" class="btn btn-danger btn-sm" >Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // fungsi untuk menampilkan detail data
    public function show(string $id)
    {
        $dataCobaCrud = CobacrudModel::find($id);
        return view('cobacrud.show', ['data' => $dataCobaCrud]);
    }

    // fungsi untuk menampilkan form create
    public function create()
    {
        return view('cobacrud.create');
    }

    // fungsi untuk menyimpan data ke dalam database
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_kategori' => 'required|string|max:100',
                'deskripsi' => 'required|string'
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
                ]);
            }

            CobacrudModel::create($request->all());
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
        $data = CobacrudModel::find($id);
        return view('cobacrud.edit', ['data' => $data]);
    }

    // fungsi untuk mengupdate data ke dalam database
    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_kategori' => 'required|string|max:100',
                'deskripsi' => 'required|string'
            ];

            $validator = Validator::make($request->all(), ['updated_at' => now(),], $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $data = CobacrudModel::find($id);
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
        $data = CobacrudModel::find($id);
        return view('cobacrud.delete', ['data' => $data]);
    }

    // fungsi untuk menghapus data dari database
    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kategori = CobacrudModel::find($id);
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
