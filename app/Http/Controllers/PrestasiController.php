<?php

namespace App\Http\Controllers;

use App\Models\PrestasiMahasiswaModel;
use App\Models\TingkatPrestasiModel;
use App\Models\KategoriModel;
use App\Models\PeriodeModel;
use App\Models\DosenPembimbingModel;
use App\Models\LaporanPrestasiModel;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PrestasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Prestasi Mahasiswa',
            'list'  => ['Prestasi Mahasiswa']
        ];
        $activeMenu = 'prestasimhs';

        return view('prestasi.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
        ]);
    }

    public function list(Request $request)
    {
        $query = PrestasiMahasiswaModel::with([
            'tingkatPrestasi',
            'kategori',
            'periode',
            'dosenPembimbing',
            'anggota'
        ]);

        if (auth()->user()->role_id == 2) {
            $query->where('id_dospem', auth()->user()->dosen->id_dospem);
        }

        if (auth()->user()->role_id == 1) {
            if ($request->filled('status_filter')) {
                $status = $request->status_filter;
                if ($status === 'null') {
                    $query->whereNull('status_verifikasi');
                } else {
                    $query->where('status_verifikasi', $status);
                }
            }
        }

        if (auth()->user()->role_id == 2) {
            if ($request->filled('status_filter')) {
                $status = $request->status_filter;
                if ($status === 'null') {
                    $query->whereNull('status_verifikasi_dospem');
                } else {
                    $query->where('status_verifikasi_dospem', $status);
                }
            }
        }

        $prestasi = $query->get();

        return DataTables::of($prestasi)
            ->addIndexColumn()
            ->addColumn('aksi', function ($item) {
                $btn = '';
                if (auth()->user()->role_id == 1) {
                    $btn  = '<a href="/prestasi/' . $item->id_prestasi . '/detail-prestasi" class="btn btn-info btn-sm">'
                        . ($item->status_verifikasi == null ? "Verifikasi" : "Edit") .
                        '</a>';
                } else if (auth()->user()->role_id == 2) {
                    $btn  = '<a href="/prestasi/' . $item->id_prestasi . '/detail-prestasi" class="btn btn-info btn-sm">'
                        . ($item->status_verifikasi_dospem == null ? "Verifikasi" : "Edit") .
                        '</a>';
                }
                return $btn;
            })
            ->addColumn('tingkat_prestasi', function ($item) {
                return $item->tingkatPrestasi->nama_tingkat_prestasi ?? '-';
            })
            ->addColumn('kategori', function ($item) {
                return $item->kategori->nama_kategori ?? '-';
            })
            ->addColumn('mahasiswa', function ($item) {
                $members = [];

                // Cek apakah relasi mahasiswa tidak null
                // if ($item->mahasiswa) {
                //     $members[] = $item->mahasiswa->nama;
                // }

                // Cek apakah relasi anggota ada dan masing-masing anggota punya relasi mahasiswa
                foreach ($item->anggota as $anggota) {
                    if ($anggota) {
                        $members[] = $anggota->nama;
                    }
                }

                return count($members) > 0 ? implode(', ', $members) : '-';
            })
            ->addColumn('status_verifikasi', function ($item) {
                if (auth()->user()->role_id == 1) {
                    if ($item->status_verifikasi === 1) {
                        return '<span class="badge bg-gradient-success">Terverifikasi</span>';
                    } elseif ($item->status_verifikasi_dospem === 0) {
                        return '<span class="badge bg-gradient-danger">Ditolak Dospem</span>';
                    } elseif ($item->status_verifikasi === 0) {
                        return '<span class="badge bg-gradient-danger">Ditolak</span>';
                    } else {
                        return '<span class="badge bg-gradient-secondary">Belum Diverifikasi</span>';
                    }
                } elseif (auth()->user()->role_id == 2) {
                    if ($item->status_verifikasi_dospem === 1) {
                        return '<span class="badge bg-gradient-success">Terverifikasi</span>';
                    } elseif ($item->status_verifikasi_dospem === 0) {
                        return '<span class="badge bg-gradient-danger">Ditolak</span>';
                    } else {
                        return '<span class="badge bg-gradient-secondary">Belum Diverifikasi</span>';
                    }
                }
                return '-';
            })
            ->rawColumns(['aksi', 'status_verifikasi'])
            ->make(true);
    }

    public function getVerifikasiDospem($id)
    {
        $prestasi = PrestasiMahasiswaModel::with([
            'dosenPembimbing',
            'anggota' // Tambahkan eager loading untuk anggota
        ])
            ->where('id_prestasi', $id)
            ->first();

        return view('prestasi.verif-dospem', [
            'prestasi' => $prestasi
        ]);
    }

    public function getVerifikasiAdmin($id)
    {
        $prestasi = PrestasiMahasiswaModel::with([
            'anggota' // Tambahkan eager loading untuk anggota
        ])
            ->where('id_prestasi', $id)
            ->first();

        return view('prestasi.verif-admin', [
            'prestasi' => $prestasi
        ]);
    }

    public function updateVerifikasiAdmin(Request $request, $id)
    {
        $prestasi = PrestasiMahasiswaModel::with(['anggota'])->find($id);
        $laporanPrestasi = LaporanPrestasiModel::where('id_prestasi', $id);

        if (!$prestasi) {
            return response()->json([
                'status' => false,
                'message' => 'Data prestasi tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status_verifikasi' => 'required|boolean',
            'keterangan' => 'nullable|string|max:255'
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
            $prestasi->status_verifikasi = $request->status_verifikasi;
            $prestasi->keterangan = $request->keterangan;
            $prestasi->save();

            // Jika prestasi diverifikasi
            if ($prestasi->status_verifikasi == 1) {
                // Dapatkan semua mahasiswa yang terlibat (pemilik dan anggota)
                $semuaMahasiswa = $prestasi->anggota;

                // Buat laporan untuk setiap mahasiswa
                foreach ($semuaMahasiswa as $mahasiswa) {
                    $laporanData = [
                        'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                        'id_prestasi' => $prestasi->id_prestasi,
                        'id_prodi' => $mahasiswa->id_prodi,
                        'id_tingkat_prestasi' => $prestasi->id_tingkat_prestasi,
                        'id_kategori' => $prestasi->id_kategori,
                    ];

                    LaporanPrestasiModel::updateOrCreate(
                        [
                            'id_prestasi' => $prestasi->id_prestasi,
                            'id_mahasiswa' => $mahasiswa->id_mahasiswa
                        ],
                        $laporanData
                    );
                }
            } else {
                // Jika prestasi ditolak, hapus semua laporan terkait prestasi ini
                LaporanPrestasiModel::where('id_prestasi', $prestasi->id_prestasi)->delete();
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Verifikasi berhasil',
                'redirect_url' => '/prestasi/' . $prestasi->id_prestasi . '/detail-prestasi'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal melakukan verifikasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateVerifikasiDospem(Request $request, $id)
    {
        $prestasi = PrestasiMahasiswaModel::find($id);

        if (!$prestasi) {
            return response()->json([
                'status' => false,
                'message' => 'Data prestasi tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status_verifikasi_dospem' => 'required|boolean',
            'keterangan' => 'nullable|string|max:255'
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
            $prestasi->status_verifikasi_dospem = $request->status_verifikasi_dospem;
            $prestasi->keterangan = $request->keterangan;
            $prestasi->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Verifikasi berhasil',
                'redirect_url' => '/prestasi/' . $prestasi->id_prestasi . '/detail-prestasi'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal melakukan verifikasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $tingkatPrestasi = TingkatPrestasiModel::all();
        $dosenPembimbing = DosenPembimbingModel::all();
        $kategori = KategoriModel::all();
        $periode = PeriodeModel::all();

        return view('prestasi.create', [
            'tingkatPrestasi' => $tingkatPrestasi,
            'dosenPembimbing' => $dosenPembimbing,
            'kategori' => $kategori,
            'periode' => $periode
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_tingkat_prestasi' => 'required|exists:tingkat_prestasi,id_tingkat_prestasi',
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'id_dospem' => 'nullable|exists:dosen_pembimbing,id_dospem',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama_prestasi' => 'required|string|max:255',
            'juara' => 'required|string|max:100',
            'tanggal_prestasi' => 'required|date',
            'id_periode' => 'required|exists:periode,id_periode',
            'deskripsi' => 'nullable|string',
            'foto_kegiatan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bukti_sertifikat' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'surat_tugas' => 'nullable|file|mimes:pdf|max:2048',
            'karya' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:5120',
            'tipe_prestasi' => 'required|in:individu,tim',
            'anggota' => 'required_if:tipe_prestasi,tim|array',
            'anggota.*.id_mahasiswa' => 'required_if:tipe_prestasi,tim|exists:mahasiswa,id_mahasiswa',
            'anggota.*.peran' => 'required_if:tipe_prestasi,tim|in:ketua,anggota'
        ], [
            'id_tingkat_prestasi.required' => 'Tingkat prestasi wajib dipilih',
            'id_kategori.required' => 'Kategori prestasi wajib dipilih',
            'nama_prestasi.required' => 'Nama prestasi wajib diisi',
            'juara.required' => 'Juara yang diraih wajib diisi',
            'tanggal_prestasi.required' => 'Tanggal prestasi wajib diisi',
            'id_periode.required' => 'Periode wajib dipilih',
            'tipe_prestasi.required' => 'Tipe prestasi wajib dipilih',
            'anggota.required_if' => 'Anggota tim wajib diisi untuk prestasi tim',
            'foto_kegiatan.image' => 'File foto kegiatan harus berupa gambar',
            'foto_kegiatan.mimes' => 'Format foto kegiatan harus jpeg, png, jpg, atau gif',
            'foto_kegiatan.max' => 'Ukuran foto kegiatan maksimal 2MB',
            'bukti_sertifikat.mimes' => 'Format bukti sertifikat harus pdf, jpeg, png, atau jpg',
            'bukti_sertifikat.max' => 'Ukuran bukti sertifikat maksimal 2MB',
            'surat_tugas.mimes' => 'Format surat tugas harus pdf',
            'surat_tugas.max' => 'Ukuran surat tugas maksimal 2MB',
            'karya.mimes' => 'Format karya harus pdf, doc, docx, ppt, atau pptx',
            'karya.max' => 'Ukuran karya maksimal 5MB'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        $validator->after(function ($validator) use ($request) {
            if ($request->tipe_prestasi === 'tim') {
                $ketuaCount = 0;
                $anggotaData = $request->anggota ?? [];
                $totalAnggota = count($anggotaData) + 1; // +1 for the owner
                $anggotaIds = [];

                // Collect all member IDs
                foreach ($request->anggota ?? [] as $member) {
                    $anggotaIds[] = $member['id_mahasiswa'];
                }

                // Add owner ID if not already in anggota
                if (!in_array($request->id_mahasiswa, $anggotaIds)) {
                    $anggotaIds[] = $request->id_mahasiswa;
                }

                // Check for duplicates
                if (count($anggotaIds) !== count(array_unique($anggotaIds))) {
                    $validator->errors()->add('anggota', 'Terdapat mahasiswa yang dipilih lebih dari satu kali');
                }

                if ($totalAnggota > 5) {
                    $validator->errors()->add('anggota', 'Maksimal 5 anggota termasuk ketua tim');
                }

                foreach ($anggotaData as $member) {
                    if ($member['peran'] === 'ketua') {
                        $ketuaCount++;
                    }
                }

                if ($ketuaCount > 1) {
                    $validator->errors()->add('anggota', 'Hanya boleh ada satu ketua dalam tim');
                }
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->except(['foto_kegiatan', 'bukti_sertifikat', 'surat_tugas', 'karya', 'anggota']);
            $data['id_mahasiswa'] = auth()->user()->mahasiswa->id_mahasiswa;

            // Handle file uploads
            $fileFields = [
                'foto_kegiatan' => 'prestasi/foto_kegiatan',
                'bukti_sertifikat' => 'prestasi/bukti_sertifikat',
                'surat_tugas' => 'prestasi/surat_tugas',
                'karya' => 'prestasi/karya'
            ];

            foreach ($fileFields as $field => $path) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $data[$field] = $file->storeAs($path, $filename, 'public');
                }
            }

            $prestasi = PrestasiMahasiswaModel::create($data);
            // Handle anggota for both individu and tim
            $anggotaData = [];
            if ($request->tipe_prestasi === 'tim') {
                $hasKetua = false;
                foreach ($request->anggota ?? [] as $member) {
                    $anggotaData[$member['id_mahasiswa']] = ['peran' => $member['peran']];
                    if ($member['peran'] === 'ketua') {
                        $hasKetua = true;
                    }
                }
                // Add the logged-in user as anggota or ketua if no ketua found in input anggota
                $peranPemilik = $hasKetua ? 'anggota' : 'ketua';
                $anggotaData[auth()->user()->mahasiswa->id_mahasiswa] = ['peran' => $peranPemilik];
            } else {
                // For individu: logged-in user always ketua in anggota relation
                $anggotaData[auth()->user()->mahasiswa->id_mahasiswa] = ['peran' => 'ketua'];
            }
            $prestasi->anggota()->sync($anggotaData);

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $prestasi,
                'message' => 'Data prestasi berhasil disimpan',
                'redirect_url' => '/mahasiswa/' . auth()->user()->mahasiswa->id_mahasiswa . '/prestasi',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            // Hapus file yang sudah diupload jika terjadi error
            foreach ($fileFields as $field => $path) {
                if (!empty($data[$field])) {
                    Storage::disk('public')->delete($data[$field]);
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data prestasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPrestasiMahasiswa($id_mahasiswa)
    {
        $breadcrumb = (object)[
            'title' => 'Prestasi Mahasiswa',
            'list'  => ['Mahasiswa', 'Prestasi Mahasiswa']
        ];

        $prestasi = \App\Models\PrestasiMahasiswaModel::with([
            'tingkatPrestasi',
            'kategori',
            'periode',
            'dosenPembimbing',
            'anggota'
        ])
            ->whereHas('anggota', function ($query) use ($id_mahasiswa) {
                $query->where('anggota_prestasi.id_mahasiswa', $id_mahasiswa);
            })
            ->get();

        return view('mahasiswa.prestasi', [
            'prestasi' => $prestasi,
            'breadcrumb' => $breadcrumb,
        ]);
    }

    public function getDetailPrestasiMahasiswa($id)
    {
        $breadcrumb = (object)[
            'title' => 'Prestasi Mahasiswa',
            'list'  => ['Mahasiswa', 'Detail Prestasi Mahasiswa']
        ];

        $prestasi = PrestasiMahasiswaModel::with([
            'tingkatPrestasi',
            'kategori',
            'periode',
            'dosenPembimbing',
            'anggota' // Tambahkan eager loading untuk anggota
        ])->where('id_prestasi', $id)->first();

        return view('prestasi.show', [
            'prestasi' => $prestasi,
            'breadcrumb' => $breadcrumb,
        ]);
    }

    public function getEditPrestasi($id)
    {
        $prestasi = PrestasiMahasiswaModel::with([
            'tingkatPrestasi',
            'kategori',
            'periode',
            'dosenPembimbing',
            'anggota' // Tambahkan eager loading untuk anggota
        ])->find($id);

        if (!$prestasi) {
            return redirect()->back()->with('error', 'Data prestasi tidak ditemukan');
        }

        $tingkatPrestasi = TingkatPrestasiModel::all();
        $kategori = KategoriModel::all();
        $periode = PeriodeModel::all();
        $dosenPembimbing = DosenPembimbingModel::all();

        return view('prestasi.edit', [
            'prestasi' => $prestasi,
            'tingkatPrestasi' => $tingkatPrestasi,
            'kategori' => $kategori,
            'periode' => $periode,
            'dosenPembimbing' => $dosenPembimbing
        ]);
    }

    public function updatePrestasi(Request $request, $id)
    {
        $prestasi = PrestasiMahasiswaModel::find($id);

        if (!$prestasi) {
            return response()->json([
                'status' => false,
                'message' => 'Data prestasi tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_prestasi' => 'required|string|max:255',
            'id_tingkat_prestasi' => 'required|exists:tingkat_prestasi,id_tingkat_prestasi',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'juara' => 'required|string|max:50',
            'tanggal_prestasi' => 'required|date',
            'id_periode' => 'required|exists:periode,id_periode',
            'id_dospem' => 'nullable|exists:dosen_pembimbing,id_dospem',
            'deskripsi' => 'nullable|string',
            'foto_kegiatan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'bukti_sertifikat' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'surat_tugas' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'karya' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:5120',
            'tipe_prestasi' => 'required|in:individu,tim',
            'anggota' => 'required_if:tipe_prestasi,tim|array',
            'anggota.*.id_mahasiswa' => 'required_if:tipe_prestasi,tim|exists:mahasiswa,id_mahasiswa',
            'anggota.*.peran' => 'required_if:tipe_prestasi,tim|in:ketua,anggota'
        ], [
            'nama_prestasi.required' => 'Nama prestasi wajib diisi',
            'id_tingkat_prestasi.required' => 'Tingkat prestasi wajib dipilih',
            'id_kategori.required' => 'Kategori prestasi wajib dipilih',
            'juara.required' => 'Juara yang diraih wajib diisi',
            'tanggal_prestasi.required' => 'Tanggal prestasi wajib diisi',
            'id_periode.required' => 'Periode wajib dipilih',
            'tipe_prestasi.required' => 'Tipe prestasi wajib dipilih',
            'anggota.required_if' => 'Anggota tim wajib diisi untuk prestasi tim',
            'foto_kegiatan.image' => 'File foto kegiatan harus berupa gambar',
            'foto_kegiatan.mimes' => 'Format foto kegiatan harus jpeg, png, atau jpg',
            'foto_kegiatan.max' => 'Ukuran foto kegiatan maksimal 2MB',
            'bukti_sertifikat.mimes' => 'Format bukti sertifikat harus pdf, jpeg, png, atau jpg',
            'bukti_sertifikat.max' => 'Ukuran bukti sertifikat maksimal 5MB',
            'surat_tugas.mimes' => 'Format surat tugas harus pdf, jpeg, png, atau jpg',
            'surat_tugas.max' => 'Ukuran surat tugas maksimal 5MB',
            'karya.mimes' => 'Format karya harus pdf, doc, docx, ppt, atau pptx',
            'karya.max' => 'Ukuran karya maksimal 5MB'
        ]);


        $validator->after(function ($validator) use ($request, $prestasi) {
            if ($request->tipe_prestasi === 'tim') {
                $ketuaCount = 0;
                $anggotaData = $request->anggota ?? [];
                $totalAnggota = count($anggotaData) + 1; // +1 for the owner
                $anggotaIds = [];

                // Collect all member IDs
                foreach ($request->anggota ?? [] as $member) {
                    $anggotaIds[] = $member['id_mahasiswa'];
                }

                // Add owner ID if not already in anggota
                if (!in_array($request->id_mahasiswa, $anggotaIds)) {
                    $anggotaIds[] = $request->id_mahasiswa;
                }

                // Check for duplicates
                if (count($anggotaIds) !== count(array_unique($anggotaIds))) {
                    $validator->errors()->add('anggota', 'Terdapat mahasiswa yang dipilih lebih dari satu kali');
                }

                if ($totalAnggota > 5) {
                    $validator->errors()->add('anggota', 'Maksimal 5 anggota termasuk ketua tim');
                }

                foreach ($anggotaData as $member) {
                    if ($member['peran'] === 'ketua') {
                        $ketuaCount++;
                    }
                }

                if ($ketuaCount > 1) {
                    $validator->errors()->add('anggota', 'Hanya boleh ada satu ketua dalam tim');
                }

                // Cek apakah mahasiswa pemilik ada di anggota yang diinput
                $pemilikAda = false;
                foreach ($anggotaData as $member) {
                    if ($member['id_mahasiswa'] == $prestasi->id_mahasiswa) {
                        $pemilikAda = true;
                        break;
                    }
                }

                if ($pemilikAda) {
                    $validator->errors()->add('anggota', 'Mahasiswa pemilik prestasi tidak perlu ditambahkan sebagai anggota');
                }
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->only([
                'nama_prestasi',
                'id_tingkat_prestasi',
                'id_kategori',
                'juara',
                'tanggal_prestasi',
                'id_periode',
                'id_dospem',
                'deskripsi',
                'tipe_prestasi'
            ]);

            // Handle file uploads
            $fileFields = [
                'foto_kegiatan' => 'prestasi/foto_kegiatan',
                'bukti_sertifikat' => 'prestasi/bukti_sertifikat',
                'surat_tugas' => 'prestasi/surat_tugas',
                'karya' => 'prestasi/karya'
            ];

            foreach ($fileFields as $field => $path) {
                if ($request->hasFile($field)) {
                    // Delete old file if exists
                    if ($prestasi->$field) {
                        Storage::disk('public')->delete($prestasi->$field);
                    }

                    // Store new file
                    $file = $request->file($field);
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $data[$field] = $file->storeAs($path, $filename, 'public');
                }
            }

            $prestasi->update($data);

            // Handle anggota for both individu and tim
            $anggotaData = [];
            if ($request->tipe_prestasi === 'tim') {
                $hasKetua = false;
                foreach ($request->anggota ?? [] as $member) {
                    $anggotaData[$member['id_mahasiswa']] = ['peran' => $member['peran']];
                    if ($member['peran'] === 'ketua') {
                        $hasKetua = true;
                    }
                }
                // Add the logged-in user as anggota or ketua if no ketua found in input anggota
                $peranPemilik = $hasKetua ? 'anggota' : 'ketua';
                $anggotaData[auth()->user()->mahasiswa->id_mahasiswa] = ['peran' => $peranPemilik];
            } else {
                // Jika berubah dari tim ke individu, hapus semua anggota
                $prestasi->anggota()->detach();
                $anggotaData[auth()->user()->mahasiswa->id_mahasiswa] = ['peran' => 'ketua'];
            }
            $prestasi->anggota()->sync($anggotaData);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Prestasi berhasil diperbarui',
                'redirect_url' => '/prestasi/' . $id . '/detail-prestasi',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui prestasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function confirmDeletePrestasi($id) // id = id prestasi
    {
        $prestasi = PrestasiMahasiswaModel::find($id);

        if (!$prestasi) {
            return redirect()->back()->with('error', 'Data prestasi tidak ditemukan');
        }

        return view('prestasi.delete', [
            'prestasi' => $prestasi
        ]);
    }

    public function deletePrestasi($id)
    {
        $prestasi = PrestasiMahasiswaModel::find($id);

        if (!$prestasi) {
            return response()->json([
                'status' => false,
                'message' => 'Data prestasi tidak ditemukan'
            ], 404);
        }

        try {
            // Hapus relasi anggota terlebih dahulu
            $prestasi->anggota()->detach();

            // Delete associated files
            $fileFields = [
                'foto_kegiatan',
                'bukti_sertifikat',
                'surat_tugas',
                'karya'
            ];

            foreach ($fileFields as $field) {
                if ($prestasi->$field) {
                    Storage::delete($prestasi->$field);
                }
            }

            $prestasi->delete();

            return response()->json([
                'status' => true,
                'message' => 'Prestasi berhasil dihapus',
                'redirect_url' => route('mahasiswa.prestasi', ['id' => $prestasi->id_mahasiswa]),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus prestasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
