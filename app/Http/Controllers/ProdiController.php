<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\ProdiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProdiController extends Controller
{
    public function index()
    {
        $activeMenu = 'prodi';
        $breadcrumb = (object)[
            'title' => 'Manajemen Program Studi',
            'list'  => ['Program Studi']
        ];

        return view('prodi.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $prodis = ProdiModel::all();

        return DataTables::of($prodis)
            ->addIndexColumn()
            ->addColumn('aksi', function ($prodi) {
                $btn  = '<button onclick="modalAction(\'' . url('/prodi/' . $prodi->id_prodi . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/prodi/' . $prodi->id_prodi . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/prodi/' . $prodi->id_prodi . '/confirm_delete') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show(string $id)
    {
        $prodi = ProdiModel::find($id);
        return view('prodi.show', ['data' => $prodi]);
    }

    public function create()
    {
        return view('prodi.create');
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_prodi' => 'required|string|max:100|unique:prodi'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            ProdiModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data prodi berhasil disimpan',
                'data' => $request->all(),
            ]);
        }
        return redirect('/prodi');
    }

    public function edit(string $id)
    {
        $prodi = ProdiModel::find($id);
        return view('prodi.edit', ['data' => $prodi]);
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_prodi' => 'required|string|max:100|unique:prodi,nama_prodi,' . $id . ',id_prodi'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $prodi = ProdiModel::find($id);
            if ($prodi) {
                $prodi->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data prodi berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/prodi');
    }

    public function confirm_delete(string $id)
    {
        $prodi = ProdiModel::find($id);
        return view('prodi.delete', ['data' => $prodi]);
    }

    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $prodi = ProdiModel::find($id);
            if ($prodi) {
                $prodi->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data prodi berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/prodi');
    }
}
