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
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        $dosen = DosenPembimbingModel::with(['prodi', 'pengguna.minatBakat'])->get();

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
                if ($dosen->pengguna->status_aktif === 1) {
                    $btn .= '<button onclick="modalAction(\'' . url('/dospem/' . $dosen->id_dospem . '/confirm_delete') . '\')" class="btn btn-danger btn-sm">Nonaktifkan</button>';
                }
                return $btn;
            })
            ->addColumn('prodi', function ($dosen) {
                return $dosen->prodi->nama_prodi;
            })
            ->addColumn('bidang_keahlian', function ($dosen) {
                return $dosen->pengguna->minatBakat->pluck('nama_kategori')->implode(', ');
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
        $dosen = DosenPembimbingModel::with(['prodi', 'pengguna.minatBakat'])->find($id);
        return view('dospem.show', ['data' => $dosen]);
    }

    public function edit(string $id)
    {
        $dosen = DosenPembimbingModel::with(['pengguna.minatBakat', 'prodi'])->find($id);
        if (!$dosen) {
            return redirect('/dospem')->with('error', 'Data dosen tidak ditemukan');
        }

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
            ]);

            if ($dosen->pengguna->username !== $request->nip) {
                $dosen->pengguna->update(['username' => $request->nip]);
                $dosen->pengguna->update(['password' => Hash::make($request->nip)]);
            }

            // update password
            if ($request->newPassword) {
                $dosen->pengguna->update(['password' => Hash::make($request->newPassword)]);
            }

            if ($request->status_aktif == 0) {
                $dosen->pengguna->update([
                    'status_aktif' => $request->status_aktif,
                    'keterangan_nonaktif' => $request->keterangan_nonaktif,
                ]);
            } else {
                $dosen->pengguna->update([
                    'status_aktif' => $request->status_aktif,
                    'keterangan_nonaktif' => null,
                ]);
            }

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

    public function getProfile($id)
    {
        $breadcrumb = (object)[
            'title' => 'Profil Dosen Pembimbing',
            'list'  => ['Dosen Pembimbing']
        ];

        $dosen = DosenPembimbingModel::with(['prodi', 'pengguna.minatBakat'])
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

    public function updateFoto($id)
    {
        $dosen = DosenPembimbingModel::find($id);
        if (!$dosen) {
            return response()->json([
                'status' => false,
                'message' => 'Data dosen tidak ditemukan'
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

            $fotoLama = $dosen->pengguna->foto;
            $fotoPath = public_path('storage/' . $fotoLama);
            if ($fotoLama && file_exists($fotoPath)) {
                unlink($fotoPath);
            }

            $file->move(public_path('storage'), $filename);

            $dosen->pengguna->update(['foto' => $filename]);

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

    public function getUpdateProfile(string $id)
    {
        $dosen = DosenPembimbingModel::with(['pengguna.minatBakat', 'prodi'])->find($id);
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
            ]);

            $dosen->pengguna->minatBakat()->sync($request->bidang_keahlian);

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

    public function confirm_delete(string $id)
    {
        $dosen = DosenPembimbingModel::find($id);
        if (!$dosen) {
            return redirect('/dospem')->with('error', 'Data dosen tidak ditemukan');
        }

        return view('dospem.delete', ['data' => $dosen]);
    }

    public function delete(string $id, Request $request)
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
                    'keterangan_nonaktif' => $request->keterangan_nonaktif,
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

    public function import()
    {
        $menuAktif = 'dospem';
        $breadcrumb = (object)[
            'title' => 'Import Data Dosen Pembimbing',
            'list'  => ['Dosen Pembimbing', 'Import']
        ];

        return view('dospem.import', [
            'breadcrumb' => $breadcrumb,
            'menuAktif' => $menuAktif
        ]);
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_dospem' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('file_dospem');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            DB::beginTransaction();
            try {
                $insertDospem = [];

                if (count($data) > 1) {
                    foreach ($data as $baris => $row) {
                        if ($baris > 1) { // Skip header
                            $pengguna = PenggunaModel::where('username', $row['A'])->first();
                            if (!$pengguna) {
                                $pengguna = PenggunaModel::create([
                                    'username' => $row['A'],
                                    'password' => Hash::make($row['A']),
                                    'role_id' => 2,
                                    'status_aktif' => true,
                                    'created_at' => now()
                                ]);
                            }

                            // // Handle multiple categories (assuming they're comma-separated in column E)
                            // $categories = explode(',', $row['E']);
                            // $categoryIds = [];
                            // foreach ($categories as $category) {
                            //     $category = trim($category);
                            //     $kategori = KategoriModel::where('nama_kategori', $category)->first();
                            //     if ($kategori) {
                            //         $categoryIds[] = $kategori->id_kategori;
                            //     }
                            // }

                            // Handle multiple categories (by ID or name) comma-separated in column E
                            $categoryIds = [];

                            if (!empty($row['E'])) {
                                $items = explode(',', $row['E']);
                                foreach ($items as $item) {
                                    $item = trim($item);

                                    // Jika input angka (ID)
                                    if (is_numeric($item)) {
                                        $kategori = KategoriModel::find($item);
                                    } else {
                                        // Jika input teks (nama)
                                        $kategori = KategoriModel::where('nama_kategori', $item)->first();
                                    }

                                    // Tambahkan ID jika kategori ditemukan
                                    if ($kategori) {
                                        $categoryIds[] = $kategori->id_kategori;
                                    }
                                }
                            }

                            // Attach categories
                            if (!empty($categoryIds)) {
                                $pengguna->minatBakat()->sync($categoryIds);
                            }

                            $insertDospem[] = [
                                'nip' => $row['A'],
                                'id_pengguna' => $pengguna->id_pengguna,
                                'nama' => $row['B'],
                                'email' => $row['C'],
                                'id_prodi' => $row['D'],
                                'created_at' => now()
                            ];
                        }
                    }

                    if (!empty($insertDospem)) {
                        DosenPembimbingModel::insertOrIgnore($insertDospem);
                    }

                    DB::commit();

                    return response()->json([
                        'status' => true,
                        'message' => 'Data dosen berhasil diimport',
                        'total' => count($insertDospem)
                    ]);
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Tidak ada data yang diimport'
                    ], 422);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal mengimport data',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
        return redirect('/dospem');
    }
}
