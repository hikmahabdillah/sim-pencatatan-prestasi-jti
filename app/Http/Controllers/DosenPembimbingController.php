<?php

namespace App\Http\Controllers;

use App\Models\DosenPembimbingModel;
use App\Models\PenggunaModel;
use App\Models\ProdiModel;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class DosenPembimbingController extends Controller
{
    public function index()
    {
        $activeMenu = 'dospem';
        $breadcrumb = (object)[
            'title' => 'Data Dosen Pembimbing',
            'list'  => ['Dosen Pembimbing']
        ];

        $prodi = ProdiModel::all();
        $kategori = KategoriModel::all();

        return view('dospem.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'prodi' => $prodi,
            'kategori' => $kategori
        ]);
    }

    public function list(Request $request)
    {
        $dosen = DosenPembimbingModel::with(['pengguna', 'prodi', 'kategori'])->get();

        if ($request->filled('status_filter')) {
            $status = $request->status_filter;
            $dosen = $dosen->filter(function ($item) use ($status) {
                return $item->pengguna && $item->pengguna->status_aktif == $status;
            });
        }

        return DataTables::of($dosen)
            ->addIndexColumn()
            ->addColumn('aksi', function ($dosen) {
                $btn  = '<button onclick="modalAction(\'' . url('/dospem/' . $dosen->id_dospem . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dospem/' . $dosen->id_dospem . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dospem/' . $dosen->id_dospem . '/confirm_delete') . '\')" class="btn btn-danger btn-sm">Nonaktifkan</button>';
                return $btn;
            })
            ->addColumn('prodi', function ($dosen) {
                return $dosen->prodi->nama_prodi;
            })
            ->addColumn('kategori', function ($dosen) {
                return $dosen->kategori->nama_kategori;
            })
            ->addColumn('status', function ($dosen) {
                return $dosen->pengguna->status_aktif ? 'Aktif' : 'Non-Aktif';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $prodi = ProdiModel::all();
        $kategori = KategoriModel::all();
        return view('dospem.create', [
            'prodi' => $prodi,
            'kategori' => $kategori
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|string|max:20|unique:dosen_pembimbing,nip',
            'nama' => 'required|string|max:200',
            'email' => 'required|email|unique:dosen_pembimbing,email',
            'id_prodi' => 'required|exists:prodi,id_prodi',
            'bidang_keahlian' => 'required|exists:kategori,id_kategori'
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
            $pengguna = PenggunaModel::create([
                'username' => $request->nip,
                'password' => Hash::make($request->nip),
                'role_id' => 2, // Role dosen
                'status_aktif' => true,
            ]);

            $dosen = DosenPembimbingModel::create([
                'nip' => $request->nip,
                'id_pengguna' => $pengguna->id_pengguna,
                'nama' => $request->nama,
                'email' => $request->email,
                'id_prodi' => $request->id_prodi,
                'bidang_keahlian' => $request->bidang_keahlian
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data dosen berhasil disimpan',
                'data' => $dosen
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data dosen',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $dosen = DosenPembimbingModel::with(['pengguna', 'prodi', 'kategori'])->find($id);
        return view('dospem.show', ['data' => $dosen]);
    }

    public function getProfile($id)
    {
        $breadcrumb = (object)[
            'title' => 'Profil Dosen Pembimbing',
            'list'  => ['Dosen Pembimbing']
        ];

        $dosen = DosenPembimbingModel::with(['prodi', 'kategori', 'pengguna'])
            ->where('id_dospem', $id)
            ->first();

        if (!$dosen) {
            return redirect('/dospem')->with('error', 'Data dosen tidak ditemukan');
        }

        return view('dospem.profile', [
            'data' => $dosen,
            'breadcrumb' => $breadcrumb,
        ]);
    }

    public function updateFoto(Request $request, $id)
    {
        $dosen = DosenPembimbingModel::find($id);
        if (!$dosen) {
            return response()->json([
                'status' => false,
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
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
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();

            // Hapus foto lama jika ada
            $fotoLama = $dosen->pengguna->foto;
            $fotoPath = public_path('storage/' . $fotoLama);
            if ($fotoLama && file_exists($fotoPath) && $fotoLama != 'default.jpg') {
                unlink($fotoPath);
            }

            // Simpan foto baru
            $file->move(public_path('storage'), $filename);

            // Update foto pengguna
            $dosen->pengguna->update(['foto' => $filename]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Foto berhasil diperbarui',
                'foto_url' => asset('storage/' . $filename)
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

    public function getUpdateProfile(string $id)
    {
        $dosen = DosenPembimbingModel::with(['pengguna', 'prodi', 'kategori'])->find($id);
        if (!$dosen) {
            return redirect('/dospem')->with('error', 'Data dosen tidak ditemukan');
        }

        $prodi = ProdiModel::all();
        $kategori = KategoriModel::all();

        return view('dospem.edit-profile', [
            'data' => $dosen,
            'prodi' => $prodi,
            'kategori' => $kategori
        ]);
    }

    public function updateProfile(Request $request, string $id)
    {
        $dosen = DosenPembimbingModel::find($id);
        if (!$dosen) {
            return response()->json([
                'status' => false,
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:200',
            'email' => 'required|email|unique:dosen_pembimbing,email,' . $id . ',id_dospem',
            'bidang_keahlian' => 'required|exists:kategori,id_kategori'
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
            $dosen->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'bidang_keahlian' => $request->bidang_keahlian
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Profil berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(string $id)
    {
        $dosen = DosenPembimbingModel::with(['pengguna', 'prodi', 'kategori'])->find($id);
        $prodi = ProdiModel::all();
        $kategori = KategoriModel::all();

        return view('dospem.edit', [
            'data' => $dosen,
            'prodi' => $prodi,
            'kategori' => $kategori
        ]);
    }

    public function update(Request $request, string $id)
    {
        $dosen = DosenPembimbingModel::find($id);
        if (!$dosen) {
            return response()->json([
                'status' => false,
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nip' => 'required|string|max:20|unique:dosen_pembimbing,nip,' . $id . ',id_dospem',
            'nama' => 'required|string|max:200',
            'email' => 'required|email|unique:dosen_pembimbing,email,' . $id . ',id_dospem',
            'id_prodi' => 'required|exists:prodi,id_prodi',
            'bidang_keahlian' => 'required|exists:kategori,id_kategori'
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
            $dosen->update([
                'nip' => $request->nip,
                'nama' => $request->nama,
                'email' => $request->email,
                'id_prodi' => $request->id_prodi,
                'bidang_keahlian' => $request->bidang_keahlian
            ]);

            if ($dosen->pengguna->username !== $request->nip) {
                $dosen->pengguna->update([
                    'username' => $request->nip,
                    'password' => Hash::make($request->nip)
                ]);
            }

            $dosen->pengguna->update([
                'status_aktif' => $request->status_aktif,
            ]);
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data dosen berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data dosen',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function confirm_delete(string $id)
    {
        $dosen = DosenPembimbingModel::find($id);
        if (!$dosen) {
            return redirect('/dospem')->with('error', 'Data dosen tidak ditemukan');
        }

        return view('dospem.delete', ['data' => $dosen]);
    }

    public function delete(string $id)
    {
        $dosen = DosenPembimbingModel::find($id);
        if (!$dosen) {
            return response()->json([
                'status' => false,
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }

        DB::beginTransaction();
        try {
            $id_pengguna = $dosen->id_pengguna;
            $pengguna = PenggunaModel::find($id_pengguna);
            if ($pengguna) {
                $pengguna->update([
                    'status_aktif' => false,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data dosen berhasil dinonaktifkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menonaktifkan data dosen',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUpdatePassword($id)
    {
        $dospem = DosenPembimbingModel::with('pengguna')->find($id);
        if (!$dospem) {
            return redirect('/dospem/' . $id . '/profile')->with('error', 'Data dosen pembimbing tidak ditemukan');
        }

        return view('dospem.edit-password', ['data' => $dospem]);
    }

    public function updatePassword(Request $request, $id)
    {
        // Cari dospem beserta data pengguna
        $dospem = DosenPembimbingModel::with('pengguna')->find($id);

        if (!$dospem) {
            return response()->json([
                'status' => false,
                'message' => 'Data dosen pembimbing tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($dospem) {
                    if (!Hash::check($value, $dospem->pengguna->password)) {
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
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Update password pengguna
            $dospem->pengguna->update([
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
