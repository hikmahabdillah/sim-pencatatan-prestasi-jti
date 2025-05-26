<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\DosenPembimbingModel;
use App\Models\MahasiswaModel;
use App\Models\PrestasiMahasiswaModel;
use App\Models\LombaModel;
use App\Models\LaporanPrestasiModel;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    public function index()
    {
        $activeMenu = 'kategori'; // digunakan untuk menandai menu aktif di sidebar
        $breadcrumb = (object)[
            'title' => 'Kategori', // untuk title halaman
            'list'  => ['Manajemen Kategori'] // untuk breadcrumb
        ];

        return view('kategori.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
        ]);
    }

    // fungsi untuk menampilkan data ke dalam DataTable
    public function list(Request $request)
    {
        $kategori = KategoriModel::all();

        return DataTables::of($kategori)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) {
                $btn  = '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->id_kategori . '/show') . '\')" class="btn btn-info btn-sm" >Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->id_kategori . '/edit') . '\')" class="btn btn-warning btn-sm" >Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->id_kategori . '/confirm_delete') . '\')" class="btn btn-danger btn-sm" >Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    //fungsi tambah data kategori
    public function create()
    {
        return view('kategori.create');
    }


    // fungsi untuk menyimpan data ke dalam database
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
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

            KategoriModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $request->all(),
            ]);
        }
        return redirect('/');
    }

    // fungsi untuk menampilkan detail data
    public function show(string $id)
    {
        $dataKategori = KategoriModel::find($id);
        return view('kategori.show', ['data' => $dataKategori]);
    }

    // fungsi untuk menampilkan form edit
    public function edit(string $id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.edit', ['data' => $kategori]);
    }

    // fungsi untuk mengupdate data ke dalam database
    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
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

            $data = KategoriModel::find($id);
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
        $data = KategoriModel::find($id);
        return view('kategori.delete', ['data' => $data]);
    }


    // fungsi untuk menghapus data dari database 
    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kategori = KategoriModel::find($id);
    
            if (!$kategori) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
    
            // Cek apakah id_kategori digunakan di tabel lain
            $digunakan = DosenPembimbingModel::where('bidang_keahlian', $id)->exists() ||
                         MahasiswaModel::where('id_kategori', $id)->exists() ||
                         PrestasiMahasiswaModel::where('id_kategori', $id)->exists() ||
                         LombaModel::where('id_kategori', $id)->exists() ||
                         LaporanPrestasiModel::where('kategori', $id)->exists();
    
            if ($digunakan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kategori tidak dapat dihapus karena masih digunakan di data lain.'
                ]);
            }
    
            // Jika tidak digunakan, hapus
            $kategori->delete();
    
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        }
    
        return redirect('/');
    }
}