<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaModel;
use App\Models\ProdiModel;
use App\Models\KategoriModel;
use App\Models\PenggunaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    public function index()
    {
        $activeMenu = 'mahasiswa';
        $breadcrumb = (object)[
            'title' => 'Data Mahasiswa',
            'list'  => ['Mahasiswa']
        ];

        $prodi = ProdiModel::all();
        $kategori = KategoriModel::all();

        return view('mahasiswa.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'prodi' => $prodi,
            'kategori' => $kategori
        ]);
    }

    public function list(Request $request)
    {
        $mahasiswa = MahasiswaModel::with(['prodi', 'kategori', 'pengguna'])->get();

        if ($request->filled('status_filter')) {
            $status = $request->status_filter;
            $mahasiswa = $mahasiswa->filter(function ($item) use ($status) {
                return $item->pengguna && $item->pengguna->status_aktif == $status;
            });
        }

        return DataTables::of($mahasiswa)
            ->addIndexColumn()
            ->addColumn('aksi', function ($mhs) {
                $btn  = '<button onclick="modalAction(\'' . url('/mahasiswa/' . $mhs->id_mahasiswa . '/show') . '\')" class="btn btn-info btn-sm" >Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/mahasiswa/' . $mhs->id_mahasiswa . '/edit') . '\')" class="btn btn-warning btn-sm" >Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/mahasiswa/' . $mhs->id_mahasiswa . '/confirm_delete') . '\')" class="btn btn-danger btn-sm" >Nonaktifkan</button> ';
                return $btn;
            })
            ->addColumn('prodi', function ($mhs) {
                return $mhs->prodi->nama_prodi;
            })
            ->addColumn('kategori', function ($mhs) {
                return $mhs->kategori->nama_kategori;
            })
            ->addColumn('status', function ($mhs) {
                return $mhs->pengguna->status_aktif ? 'Aktif' : 'Non-Aktif';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show(string $id)
    {
        $mahasiswa = MahasiswaModel::with(['prodi', 'kategori', 'pengguna'])->find($id);
        return view('mahasiswa.show', ['data' => $mahasiswa]);
    }

    public function create()
    {
        $prodi = ProdiModel::all();
        $kategori = KategoriModel::all();
        return view('mahasiswa.create', ['prodi' => $prodi, 'kategori' => $kategori]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required|string|max:20|unique:mahasiswa,nim',
            'nama' => 'required|string|max:200',
            'angkatan' => 'required|integer',
            'email' => 'required|email|unique:mahasiswa,email',
            'no_hp' => 'required|string|max:20|unique:mahasiswa,no_hp',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'id_prodi' => 'required|exists:prodi,id_prodi',
            'id_kategori' => 'required|exists:kategori,id_kategori'
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
            // Create aku pengguna
            $pengguna = PenggunaModel::create([
                'username' => $request->nim,
                'password' => Hash::make($request->nim),
                'role_id' => 3, // Role for mahasiswa
                'status_aktif' => true,
            ]);

            // Create mahasiswa data
            $mahasiswa = MahasiswaModel::create([
                'nim' => $request->nim,
                'id_pengguna' => $pengguna->id_pengguna,
                'nama' => $request->nama,
                'angkatan' => $request->angkatan,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'id_prodi' => $request->id_prodi,
                'id_kategori' => $request->id_kategori
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data mahasiswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(string $id)
    {
        $mahasiswa = MahasiswaModel::with(['pengguna', 'prodi', 'kategori'])->find($id);
        if (!$mahasiswa) {
            return redirect('/mahasiswa')->with('error', 'Data mahasiswa tidak ditemukan');
        }

        $prodi = ProdiModel::all();
        $kategori = KategoriModel::all();

        return view('mahasiswa.edit', [
            'data' => $mahasiswa,
            'prodi' => $prodi,
            'kategori' => $kategori
        ]);
    }

    public function update(Request $request, string $id)
    {
        $mahasiswa = MahasiswaModel::find($id);
        if (!$mahasiswa) {
            return response()->json([
                'status' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nim' => 'required|string|max:20|unique:mahasiswa,nim,' . $id . ',id_mahasiswa',
            'nama' => 'required|string|max:200',
            'angkatan' => 'required|integer',
            'email' => 'required|email|unique:mahasiswa,email,' . $id . ',id_mahasiswa',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'id_prodi' => 'required|exists:prodi,id_prodi',
            'id_kategori' => 'required|exists:kategori,id_kategori'
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
            // Update data mahasiswa
            $mahasiswa->update([
                'nim' => $request->nim,
                'nama' => $request->nama,
                'angkatan' => $request->angkatan,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'id_prodi' => $request->id_prodi,
                'id_kategori' => $request->id_kategori
            ]);

            // Update username pengguna jika NIM berubah
            if ($mahasiswa->pengguna->username !== $request->nim) {
                $mahasiswa->pengguna->update(['username' => $request->nim]);
                $mahasiswa->pengguna->update(['password' => Hash::make($request->nim)]);
            }

            $mahasiswa->pengguna->update([
                'status_aktif' => $request->status_aktif,
            ]);
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data mahasiswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request, string $id)
    {
        $mahasiswa = MahasiswaModel::find($id);
        if (!$mahasiswa) {
            return response()->json([
                'status' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:200',
            'email' => 'required|email|unique:mahasiswa,email,' . $id . ',id_mahasiswa',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'id_kategori' => 'required|exists:kategori,id_kategori'
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
            // Update data mahasiswa
            $mahasiswa->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'tanggal_lahir' => $request->tanggal_lahir,
                'id_kategori' => $request->id_kategori
            ]);
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data mahasiswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUpdateProfile(string $id)
    {
        $mahasiswa = MahasiswaModel::with(['pengguna', 'prodi', 'kategori'])->find($id);
        if (!$mahasiswa) {
            return redirect('/mahasiswa')->with('error', 'Data mahasiswa tidak ditemukan');
        }

        $prodi = ProdiModel::all();
        $kategori = KategoriModel::all();

        return view('mahasiswa.edit-profile', [
            'data' => $mahasiswa,
            'prodi' => $prodi,
            'kategori' => $kategori
        ]);
    }

    public function confirm_delete(string $id)
    {
        $mahasiswa = MahasiswaModel::find($id);
        if (!$mahasiswa) {
            return redirect('/mahasiswa')->with('error', 'Data mahasiswa tidak ditemukan');
        }

        return view('mahasiswa.delete', ['data' => $mahasiswa]);
    }

    public function delete(string $id)
    {
        $mahasiswa = MahasiswaModel::find($id);
        if (!$mahasiswa) {
            return response()->json([
                'status' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }

        DB::beginTransaction();
        try {
            $id_pengguna = $mahasiswa->id_pengguna;
            $pengguna = PenggunaModel::find($id_pengguna);
            if ($pengguna) {
                $pengguna->update([
                    'status_aktif' => false,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil dinonaktifkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menonaktifkan data mahasiswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getProfile($id)
    {
        $breadcrumb = (object)[
            'title' => 'Profile Mahasiswa',
            'list'  => ['Profile Mahasiswa']
        ];
        $mahasiswa = MahasiswaModel::with(['prodi', 'kategori', 'pengguna'])->where('id_mahasiswa', $id)->first();
        if (!$mahasiswa) {
            return redirect('/mahasiswa/' . $id . '/profile')->with('error', 'Data mahasiswa tidak ditemukan');
        }

        return view('mahasiswa.profile', [
            'data' => $mahasiswa,
            'breadcrumb' => $breadcrumb,
        ]);
    }

    public function updateFoto($id)
    {
        $mahasiswa = MahasiswaModel::find($id);
        if (!$mahasiswa) {
            return response()->json([
                'status' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
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
            $fotoLama = $mahasiswa->pengguna->foto;
            $fotoPath = public_path('storage/' . $fotoLama);
            if ($fotoLama && file_exists($fotoPath)) {
                unlink($fotoPath);
            }

            // Simpan foto baru
            $file->move(public_path('storage'), $filename);

            // Update foto pengguna
            $mahasiswa->pengguna->update(['foto' => $filename]);

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
        $mahasiswa = MahasiswaModel::with('pengguna')->find($id);
        if (!$mahasiswa) {
            return redirect('/mahasiswa/' . $id . '/profile')->with('error', 'Data mahasiswa tidak ditemukan');
        }

        return view('mahasiswa.edit-password', ['data' => $mahasiswa]);
    }

    public function updatePassword(Request $request, $id)
    {
        // Cari mahasiswa beserta data pengguna
        $mahasiswa = MahasiswaModel::with('pengguna')->find($id);

        if (!$mahasiswa) {
            return response()->json([
                'status' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($mahasiswa) {
                    if (!Hash::check($value, $mahasiswa->pengguna->password)) {
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
            $mahasiswa->pengguna->update([
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
