<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        $activeMenu = 'role'; // digunakan untuk menandai menu aktif di sidebar
        $breadcrumb = (object)[
            'title' => 'Manajemen Role', // untuk title halaman
            'list'  => ['Role'] // untuk breadcrumb
        ];

        return view('role.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
        ]);
    }

    // fungsi untuk menampilkan data ke dalam DataTable
    public function list(Request $request)
    {
        $roles = RoleModel::all();

        return DataTables::of($roles)
            ->addIndexColumn()
            ->addColumn('aksi', function ($role) {
                $btn  = '<button onclick="modalAction(\'' . url('/role/' . $role->role_id . '/show') . '\')" class="btn btn-info btn-sm" >Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/role/' . $role->role_id . '/edit') . '\')" class="btn btn-warning btn-sm" >Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/role/' . $role->role_id . '/confirm_delete') . '\')" class="btn btn-danger btn-sm" >Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // fungsi untuk menampilkan detail data
    public function show(string $id)
    {
        $role = RoleModel::find($id);
        return view('role.show', ['data' => $role]);
    }

    // fungsi untuk menampilkan form create
    public function create()
    {
        return view('role.create');
    }

    // fungsi untuk menyimpan data ke dalam database
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_role' => 'required|string|max:100|unique:role'
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

            RoleModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $request->all(),
            ]);
        }
        return redirect('/role');
        // dd($request->all());
    }

    // fungsi untuk menampilkan form edit
    public function edit(string $id)
    {
        $data = RoleModel::find($id);
        return view('role.edit', ['data' => $data]);
    }

    // fungsi untuk mengupdate data ke dalam database
    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_role' => 'required|string|max:100|unique:role'
            ];

            $validator = Validator::make($request->all(), ['updated_at' => now(),], $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $data = RoleModel::find($id);
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
        return redirect('/role');
    }

    // fungsi untuk menampilkan konfirmasi hapus
    public function confirm_delete(string $id)
    {
        $data = RoleModel::find($id);
        return view('role.delete', ['data' => $data]);
    }

    // fungsi untuk menghapus data dari database
    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $role = RoleModel::find($id);
            if ($role) {
                $role->delete();
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

        return redirect('/role');
    }
}
