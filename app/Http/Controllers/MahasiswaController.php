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
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        $mahasiswa = MahasiswaModel::with(['prodi', 'pengguna.minatBakat'])->get();

        // jika ada isi dari requests front end maka filter

        if ($request->filled('status_filter')) {
            $status = $request->status_filter;
            $mahasiswa = $mahasiswa->filter(function ($item) use ($status) {
                return $item->pengguna && $item->pengguna->status_aktif == $status;
            });
        }

        // Tampilkan Data Mahasiswa ke dalam DataTables
        return DataTables::of($mahasiswa)
            ->addIndexColumn()
            ->addColumn('aksi', function ($mhs) {
                $btn  = '<button onclick="modalAction(\'' . url('/mahasiswa/' . $mhs->id_mahasiswa . '/show') . '\')" class="btn btn-info btn-sm" >Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/mahasiswa/' . $mhs->id_mahasiswa . '/edit') . '\')" class="btn btn-warning btn-sm" >Edit</button> ';
                if ($mhs->pengguna->status_aktif === 1) {
                    $btn .= '<button onclick="modalAction(\'' . url('/mahasiswa/' . $mhs->id_mahasiswa . '/confirm_delete') . '\')" class="btn btn-danger btn-sm" >Nonaktifkan</button> ';
                }
                return $btn;
            })
            ->addColumn('prodi', function ($mhs) {
                return $mhs->prodi->nama_prodi;
            })
            ->addColumn('minat_bakat', function ($mhs) {
                return $mhs->pengguna->minatBakat->pluck('nama_kategori');
            })
            ->addColumn('status', function ($mhs) {
                return $mhs->pengguna->status_aktif ? 'Aktif' : 'Non-Aktif';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show(string $id)
    {
        $mahasiswa = MahasiswaModel::with(['prodi', 'pengguna'])->find($id);
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
            'no_hp' => 'required|numeric|digits_between:10,20|unique:mahasiswa,no_hp',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'id_prodi' => 'required|exists:prodi,id_prodi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        // Untuk memastikan kalau ada error, semua perubahan akan dibatalkan
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
            ]);
            // Jika semua proses berhasil, commit transaksi 
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {
            // Jika terjadi error saat menyimpan data, batalkan semua perubahan dan kirim pesan gagal beserta error-nya.
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
        $mahasiswa = MahasiswaModel::with(['pengguna', 'prodi'])->find($id);
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
            'no_hp' => 'required|numeric|digits_between:3,20',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
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
            ]);

            // Update username pengguna jika NIM berubah
            if ($mahasiswa->pengguna->username !== $request->nim) {
                $mahasiswa->pengguna->update(['username' => $request->nim]);
                $mahasiswa->pengguna->update(['password' => Hash::make($request->nim)]);
            }

            // update password
            if ($request->newPassword) {
                $mahasiswa->pengguna->update(['password' => Hash::make($request->newPassword)]);
            }


            if ($request->status_aktif == 0) {
                $mahasiswa->pengguna->update([
                    'status_aktif' => $request->status_aktif,
                    'keterangan_nonaktif' => $request->keterangan_nonaktif,
                ]);
            } else {
                $mahasiswa->pengguna->update([
                    'status_aktif' => $request->status_aktif,
                    'keterangan_nonaktif' => null,
                ]);
            }

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
            'no_hp' => 'required|numeric|digits_between:10,20',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'minat_bakat' => 'required|array|max:3',
            'minat_bakat.*' => 'exists:kategori,id_kategori'
        ], [
            'minat_bakat.max' => 'Maksimal memilih 3 minat bakat',
            'minat_bakat.required' => 'Pilih minimal satu minat bakat'
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
            ]);

            $mahasiswa->pengguna->minatBakat()->sync($request->minat_bakat);

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
        $mahasiswa = MahasiswaModel::with(['pengguna', 'prodi'])->find($id);
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

    public function delete(string $id, Request $request)
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
                    'keterangan_nonaktif' => $request->keterangan_nonaktif,
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
        $mahasiswa = MahasiswaModel::with(['prodi', 'pengguna'])->where('id_mahasiswa', $id)->first();
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
            $file = request()->file('foto'); //ambil foto yang diupload
            $filename = time() . '.' . $file->getClientOriginalExtension(); //Beri nama file berdasarkan waktu saat ini + ekstensi asli,  agar nama file unik

            // Hapus foto lama jika ada
            $fotoLama = $mahasiswa->pengguna->foto;
            $fotoPath = public_path('storage/' . $fotoLama);
            // Cari file lama di folder storage/
            if ($fotoLama && file_exists($fotoPath)) {
                unlink($fotoPath); //jika ada hapus file lama tersebut di penyimpanan
            }

            // Simpan foto baru ke storage
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
                // $value = password yang diinput oleh user
                // $attribute: nama field yang sedang divalidasi ('current_password').
                // use Digunakan untuk membawa variabel dari luar fungsi ke dalam fungsi.
                // Kenapa perlu use? Karena variabel $mahasiswa tidak tersedia langsung di dalam fungsi, jadi perlu dibawa masuk dengan use.
                function ($attribute, $value, $fail) use ($mahasiswa) {
                    // digunakan untuk membandingkan password input (plaintext) dengan password yang tersimpan di database (sudah di-hash).
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
    public function import()
    {
        $menuAktif = 'mahasiswa';
        $breadcrumb = (object)[
            'title' => 'Import Data Mahasiswa',
            'list'  => ['Mahasiswa', 'Import']
        ];

        return view('mahasiswa.import', [
            'breadcrumb' => $breadcrumb,
            'menuAktif' => $menuAktif
        ]);
    }
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_mahasiswa' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('file_mahasiswa');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            DB::beginTransaction();
            try {
                $insertMahasiswa = [];

                if (count($data) > 1) {
                    foreach ($data as $baris => $row) {
                        if ($baris > 1) { // Skip header
                            // Cek apakah pengguna sudah ada berdasarkan username (NIM)
                            $pengguna = PenggunaModel::where('username', $row['A'])->first();
                            if (!$pengguna) {
                                $pengguna = PenggunaModel::create([
                                    'username' => $row['A'],
                                    'password' => Hash::make($row['A']),
                                    'role_id' => 3,
                                    'status_aktif' => true,
                                    'created_at' => now()
                                ]);
                            }

                            // Handle multiple categories (assuming they're comma-separated in column J)
                            // $categories = explode(',', $row['J']);
                            // $categoryIds = [];
                            // foreach ($categories as $category) {
                            //     $category = trim($category);
                            //     $kategori = KategoriModel::where('nama_kategori', $category)->first();
                            //     if ($kategori) {
                            //         $categoryIds[] = $kategori->id_kategori;
                            //     }
                            // }
                            // Handle multiple categories (by ID or name) comma-separated in column J
                            $categoryIds = [];

                            if (!empty($row['J'])) {
                                $items = explode(',', $row['J']);
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

                            // Siapkan data mahasiswa
                            $insertMahasiswa[] = [
                                'nim' => $row['A'],
                                'id_pengguna' => $pengguna->id_pengguna,
                                'nama' => $row['B'],
                                'angkatan' => $row['C'],
                                'email' => $row['D'],
                                'no_hp' => $row['E'],
                                'alamat' => $row['F'],
                                'tanggal_lahir' => $row['G'],
                                'jenis_kelamin' => $row['H'],
                                'id_prodi' => $row['I'],
                                'created_at' => now()
                            ];
                        }
                    }


                    // Insert data mahasiswa sekaligus, abaikan jika duplikat
                    if (!empty($insertMahasiswa)) {
                        MahasiswaModel::insertOrIgnore($insertMahasiswa);
                    }

                    DB::commit();

                    return response()->json([
                        'status' => true,
                        'message' => 'Data mahasiswa berhasil diimport',
                        'total' => count($insertMahasiswa)
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
        return redirect('/mahasiswa');
    }
}
