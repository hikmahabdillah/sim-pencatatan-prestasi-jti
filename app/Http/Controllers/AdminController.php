<?php

namespace App\Http\Controllers;

use App\Models\AdminModel;
use App\Models\PenggunaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $activeMenu = 'admin';
        $breadcrumb = (object)[
            'title' => 'Data Admin',
            'list'  => ['Admin']
        ];

        return view('admin.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $admin = AdminModel::with('pengguna')
            ->where('id_admin', '!=', Auth::user()->admin->id_admin)
            ->get();

        return DataTables::of($admin)
            ->addIndexColumn()
            ->addColumn('aksi', function ($admin) {
                $btn  = '<button onclick="modalAction(\'' . url('/admin/' . $admin->id_admin . '/show') . '\')" class="btn btn-info btn-sm" >Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/' . $admin->id_admin . '/edit') . '\')" class="btn btn-warning btn-sm" >Edit</button> ';
                if ($admin->pengguna->status_aktif === 1) {
                    $btn .= '<button onclick="modalAction(\'' . url('/admin/' . $admin->id_admin . '/confirm_delete') . '\')" class="btn btn-danger btn-sm" >Nonaktifkan</button> ';
                }
                return $btn;
            })
            ->addColumn('status', function ($admin) {
                return $admin->pengguna->status_aktif ? 'Aktif' : 'Non-Aktif';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show(string $id)
    {
        $admin = AdminModel::with(['pengguna'])->find($id);
        return view('admin.show', ['data' => $admin]);
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:admin,username',
            'nama_admin' => 'required|string|max:200',
            'email' => 'required|email|unique:admin,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Create user account first
            $pengguna = PenggunaModel::create([
                'username' => $request->username,
                'password' => Hash::make($request->username),
                'role_id' => 1, // Role untuk admin
                'status_aktif' => true,
            ]);

            // Create admin data
            $admin = AdminModel::create([
                'username' => $request->username,
                'id_pengguna' => $pengguna->id_pengguna,
                'nama_admin' => $request->nama_admin,
                'email' => $request->email
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data admin berhasil disimpan',
                'data' => $admin
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data admin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(string $id)
    {
        $admin = AdminModel::with(['pengguna'])->find($id);
        if (!$admin) {
            return redirect('/admin')->with('error', 'Data admin tidak ditemukan');
        }

        return view('admin.edit', ['data' => $admin]);
    }

    public function update(Request $request, string $id)
    {
        $admin = AdminModel::find($id);
        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Data admin tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:admin,username,' . $id . ',id_admin',
            'nama_admin' => 'required|string|max:200',
            'email' => 'required|email|unique:admin,email,' . $id . ',id_admin'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Update data admin
            $admin->update([
                'username' => $request->username,
                'nama_admin' => $request->nama_admin,
                'email' => $request->email
            ]);

            // Update username pengguna jika username berubah
            if ($admin->pengguna->username !== $request->username) {
                $admin->pengguna->update(['username' => $request->username]);
                $admin->pengguna->update(['password' => Hash::make($request->username)]);
            }

            $admin->pengguna->update([
                'status_aktif' => $request->status_aktif,
            ]);
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data admin berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data admin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $admin = AdminModel::with(['pengguna'])->find($id);
        if (!$admin || !$admin->pengguna) {
            return response()->json([
                'status' => false,
                'message' => 'Data admin tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_admin' => 'required|string|max:200',
            'email' => 'required|email|unique:admin,email,' . $id . ',id_admin',
            // 'status_aktif' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Update data admin
            $admin->update([
                'nama_admin' => $request->nama_admin,
                'email' => $request->email
            ]);

            // Update status aktif
            // $admin->pengguna->update([
            //     'status_aktif' => $request->status_aktif
            // ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Profile admin berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui profile admin',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getUpdateProfile(string $id)
    {
        $admin = AdminModel::with(['pengguna'])->find($id);
        if (!$admin) {
            return redirect('/admin')->with('error', 'Data Admin tidak ditemukan');
        }

        return view('admin.edit-profile', [
            'data' => $admin,
        ]);
    }
    // TAMBAHAN METHOD UNTUK EDIT PROFILE
    // public function editProfile($id)
    // {
    //     $admin = AdminModel::with(['pengguna'])->find($id);
    //     if (!$admin) {
    //         return redirect('/admin')->with('error', 'Data admin tidak ditemukan');
    //     }

    //     return view('admin.edit-profile', ['data' => $admin]);
    // }

    public function confirm_delete(string $id)
    {
        $admin = AdminModel::find($id);
        if (!$admin) {
            return redirect('/admin')->with('error', 'Data admin tidak ditemukan');
        }

        return view('admin.delete', ['data' => $admin]);
    }

    public function delete(string $id)
    {
        $admin = AdminModel::find($id);
        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Data admin tidak ditemukan'
            ], 404);
        }

        DB::beginTransaction();
        try {
            $id_pengguna = $admin->id_pengguna;
            $pengguna = PenggunaModel::find($id_pengguna);
            if ($pengguna) {
                $pengguna->update([
                    'status_aktif' => false,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data admin berhasil dinonaktifkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menonaktifkan data admin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getProfile($id)
    {
        $breadcrumb = (object)[
            'title' => 'Data Admin',
            'list'  => ['Admin']
        ];
        $admin = AdminModel::with(['pengguna'])->where('id_admin', $id)->first();
        if (!$admin) {
            return redirect('/admin')->with('error', 'Data admin tidak ditemukan');
        }

        return view('admin.profile', [
            'data' => $admin,
            'breadcrumb' => $breadcrumb,
        ]);
    }

    public function updateFoto($id)
    {
        $admin = AdminModel::with(['pengguna'])->find($id);
        if (!$admin || !$admin->pengguna) {
            return response()->json([
                'status' => false,
                'message' => 'Data admin tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make(request()->all(), [
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $file = request()->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();

            // Hapus foto lama jika ada
            $fotoLama = $admin->pengguna->foto;
            $fotoPath = public_path('storage/' . $fotoLama);
            if ($fotoLama && file_exists($fotoPath)) {
                unlink($fotoPath);
            }

            // Simpan foto baru
            $file->move(public_path('storage'), $filename);

            // Update foto pengguna
            $admin->pengguna->update(['foto' => $filename]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Foto berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui foto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUpdatePassword($id)
    {
        $admin = AdminModel::with('pengguna')->find($id);
        if (!$admin) {
            return redirect('/admin/' . $id . '/profile')->with('error', 'Data admin tidak ditemukan');
        }

        return view('admin.edit-password', ['data' => $admin]);
    }

    public function updatePassword(Request $request, $id)
    {
        // Cari admin beserta data pengguna
        $admin = AdminModel::with('pengguna')->find($id);

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Data admin tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($admin) {
                    if (!Hash::check($value, $admin->pengguna->password)) {
                        $fail('Password saat ini salah');
                    }
                }
            ],
            'new_password' => 'required|string|min:6|different:current_password',
            'new_password_confirmation' => 'required|same:new_password'
        ], [
            'new_password.different' => 'Password baru harus berbeda dengan password saat ini',
            'new_password_confirmation.same' => 'Konfirmasi password tidak cocok'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Update password pengguna
            $admin->pengguna->update([
                'password' => Hash::make($request->new_password)
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Password berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui password',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
