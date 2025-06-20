<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\KategoriModel;
use App\Models\PenggunaModel;
use App\Models\TingkatPrestasiModel;
use App\Models\PeriodeModel;
use App\Models\MahasiswaModel;
use App\Models\RoleModel;
use App\Models\RekomendasiLombaModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class LombaController extends Controller
{
    public function indexAdmin()
    {
        $activeMenu = 'manajemen_lomba';
        $breadcrumb = (object)[
            'title' => 'Manajemen Lomba',
            'list' => [' Manajemen Lomba']
        ];

        $kategori = KategoriModel::all();

        return view('lomba.indexAdmin', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori,
        ]);
    }


    public function indexMahasiswa()
    {
        $activeMenu = 'lomba';
        $breadcrumb = (object)[
            'title' => 'Lomba',
            'list' => ['Lomba']
        ];

        $kategori = KategoriModel::all();

        // Ambil id mahasiswa yg login
        $idMahasiswa = auth()->user()->mahasiswa->id_mahasiswa;

        // Ambil rekomendasi lomba dari model RekomendasiLomba
        $rekomendasi = RekomendasiLombaModel::where('id_mahasiswa', $idMahasiswa)
            ->orderByDesc('skor_moora')
            ->get();

        // Ambil data lomba yang terkait
        $lombaIds = $rekomendasi->pluck('id_lomba');
        $lombaList = LombaModel::with('kategoris')
            ->whereIn('id_lomba', $lombaIds)
            ->get()
            ->keyBy('id_lomba');

        // Gabungkan data rekomendasi dengan data lomba lengkap
        $rekomLomba = $rekomendasi->map(function ($rek) use ($lombaList) {
            $lomba = $lombaList[$rek->id_lomba];
            return [
                'id_lomba' => $lomba->id_lomba,
                'nama_lomba' => $lomba->nama_lomba,
                'kategoris' => $lomba->kategoris->map(function ($kat) {
                    return [
                        'id_kategori' => $kat->id_kategori,
                        'nama_kategori' => $kat->nama_kategori,
                    ];
                }),
                'deskripsi' => $lomba->deskripsi,
                'link_pendaftaran' => $lomba->link_pendaftaran,
                'foto' => $lomba->foto,
                'skor_moora' => $rek->skor_moora,
            ];
        });

        return view('lomba.indexMahasiswa', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori,
            'rekomLomba' => $rekomLomba,
        ]);
    }

    public function indexDosen()
    {
        $activeMenu = 'Lomba';
        $breadcrumb = (object)[
            'title' => 'Lomba',
            'list' => ['Lomba']
        ];

        $kategori = KategoriModel::all();

        return view('lomba.indexDosen', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori,
        ]);
    }

    public function getRekombyDosen(Request $request)
    {
        $keyword = $request->keyword;
        $kategori = $request->kategori;

        $user = auth()->user();
        $pengusulId = $user->id_pengguna; // ini yang kamu simpan di rekomendasi

        $query = RekomendasiLombaModel::with([
            'mahasiswa:id_mahasiswa,nim,nama',
            'lomba.kategoris'
        ])
            ->where('id_pengusul', $pengusulId)
            ->where('role_pengusul', 2)
            ->when($keyword, function ($q) use ($keyword) {
                $q->where(function ($subQuery) use ($keyword) {
                    $subQuery->whereHas('lomba', fn($l) => $l->where('nama_lomba', 'like', "%$keyword%"))
                        ->orWhereHas('mahasiswa', fn($m) => $m->where('nama', 'like', "%$keyword%"))
                        ->orWhereHas('mahasiswa', fn($m) => $m->where('nim', 'like', "%$keyword%"));
                });
            })
            ->when($kategori, function ($q) use ($kategori) {
                $q->whereHas('lomba.kategoris', fn($k) => $k->where('kategori.id_kategori', $kategori));
            })
            ->orderBy('tanggal_rekomendasi', 'asc');

        return response()->json([
            'data' => $query->get()
        ]);
    }

    public function inputLomba()
    {
        $activeMenu = 'input_lomba';
        $breadcrumb = (object)[
            'title' => 'Input Lomba',
            'list' => [' Input Lomba']
        ];

        $kategori = KategoriModel::all();

        return view('lomba.inputLomba', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori,
        ]);
    }

    public function listAdmin(Request $request)
    {
        $keyword = $request->input('keyword');
        $filterKategori = $request->input('kategori');
        $statusVerifikasi = $request->input('status_verifikasi');

        $lomba = LombaModel::with('kategoris')
            ->when($filterKategori, function ($query, $filterKategori) {
                return $query->whereHas('kategoris', function ($q) use ($filterKategori) {
                    $q->where('kategori.id_kategori', $filterKategori);
                });
            })
            ->when($keyword, function ($query, $keyword) {
                return $query->where('nama_lomba', 'like', "%{$keyword}%");
            })
            ->when($statusVerifikasi === 'belum', function ($query) {
                return $query->whereNull('status_verifikasi');
            })
            ->when($statusVerifikasi === 'sudah', function ($query) {
                return $query->whereIn('status_verifikasi', [0, 1]);
            })
            ->when($statusVerifikasi === 'rekom', function ($query) {
                return $query->whereIn('status_verifikasi', [1]);
            })
            ->distinct()
            ->get();

        $data = $lomba->map(function ($item) use ($statusVerifikasi) {
            $aksi = '';

            if ($statusVerifikasi === 'rekom') {
                $aksi = '<a href="' . url('/rekomendasi/lomba/' . $item->id_lomba) . '" class="btn btn-primary btn-sm">Rekomendasi Mahasiswa</a>';
            } else {
                $aksi = auth()->user()->role_id == 1
                    ? '<a href="' . url('/lomba/' . $item->id_lomba . '/show') . '" class="btn btn-info btn-sm">Detail</a> ' .
                    '<button onclick="modalAction(\'' . url('/lomba/' . $item->id_lomba . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ' .
                    '<button onclick="modalAction(\'' . url('/lomba/' . $item->id_lomba . '/confirm_delete') . '\')" class="btn btn-danger btn-sm">Hapus</button>'
                    : '<a href="' . url('/lomba/' . $item->id_lomba . '/showMahasiswa') . '" class="btn btn-info btn-sm">Detail</a>';
            }

            return [
                'id_lomba' => $item->id_lomba,
                'nama_lomba' => $item->nama_lomba,
                'kategoris' => $item->kategoris->map(function ($k) {
                    return [
                        'id_kategori' => $k->id_kategori,
                        'nama_kategori' => $k->nama_kategori,
                    ];
                }),
                'deskripsi' => $item->deskripsi,
                'link_pendaftaran' => $item->link_pendaftaran,
                'foto' => $item->foto,
                'status_verifikasi' => $item->status_verifikasi,
                'aksi' => $aksi,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function listLomba(Request $request)
    {
        $keyword = $request->input('keyword');
        $filterKategori = $request->input('kategori');
        $statusVerifikasi = $request->input('status_verifikasi');

        $lomba = LombaModel::with('kategoris')
            ->where('status_verifikasi', 1)
            ->whereDate('deadline_pendaftaran', '>=', Carbon::today())
            ->when($filterKategori, function ($query, $filterKategori) {
                return $query->whereHas('kategoris', function ($q) use ($filterKategori) {
                    $q->where('kategori.id_kategori', $filterKategori);
                });
            })
            ->when($keyword, function ($query, $keyword) {
                return $query->where('nama_lomba', 'like', "%{$keyword}%");
            })
            ->get();

        $data = $lomba->map(function ($item) {
            return [
                'id_lomba' => $item->id_lomba,
                'nama_lomba' => $item->nama_lomba,
                'kategoris' => $item->kategoris->map(function ($kat) {
                    return [
                        'id_kategori' => $kat->id_kategori,
                        'nama_kategori' => $kat->nama_kategori,
                    ];
                }),
                'deskripsi' => $item->deskripsi,
                'link_pendaftaran' => $item->link_pendaftaran,
                'foto' => $item->foto,
                'status_verifikasi' => $item->status_verifikasi,
                'aksi' => auth()->user()->role_id == 2
                    ? '<a href="' . url('/lomba/' . $item->id_lomba . '/showDosen') . '" class="btn btn-info btn-sm">Detail</a>'
                    : (auth()->user()->role_id == 3
                        ? '<a href="' . url('/lomba/' . $item->id_lomba . '/showMahasiswa') . '" class="btn btn-info btn-sm">Detail</a>'
                        : ''
                    ),
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function listRekom(Request $request)
    {
        $idMahasiswa = auth()->user()->mahasiswa->id_mahasiswa;

        // Selalu generate ulang rekomendasi
        app()->call('App\Http\Controllers\RekomendasiLombaController@prosesRekomendasi', [
            'idMahasiswa' => $idMahasiswa
        ]);

        // Ambil data rekomendasi terbaru dan urutkan berdasarkan skor_moora desc
        $query = RekomendasiLombaModel::where('id_mahasiswa', $idMahasiswa)
            ->orderByDesc('skor_moora');

        // Filter tipe_lomba jika ada
        if ($request->has('tipe_lomba') && in_array($request->tipe_lomba, ['tim', 'individu'])) {
            $query->whereHas('lomba', function ($q) use ($request) {
                $q->where('tipe_lomba', $request->tipe_lomba);
            });
        }

        $rekomendasi = $query->get();
        $lombaIds = $rekomendasi->pluck('id_lomba');

        $lombaQuery = LombaModel::with('kategoris')->whereIn('id_lomba', $lombaIds)->where('status_verifikasi', 1);

        if ($request->filled('keyword')) {
            $lombaQuery->where('nama_lomba', 'like', '%' . $request->keyword . '%');
        }

        $lombaList = $lombaQuery->get()->keyBy('id_lomba');

        $filteredRekom = $rekomendasi->filter(fn($rek) => $lombaList->has($rek->id_lomba));

        $rank = 1;
        $data = $filteredRekom->map(function ($rek) use ($lombaList, &$rank) {
            $lomba = $lombaList[$rek->id_lomba];

            return [
                'id_lomba' => $lomba->id_lomba,
                'nama_lomba' => $lomba->nama_lomba,
                'kategoris' => $lomba->kategoris->map(fn($kat) => [
                    'id_kategori' => $kat->id_kategori,
                    'nama_kategori' => $kat->nama_kategori,
                ]),
                'deskripsi' => $lomba->deskripsi,
                'link_pendaftaran' => $lomba->link_pendaftaran,
                'foto' => $lomba->foto,
                'skor_moora' => $rek->skor_moora,
                'aksi' => '<a href="' . url('/lomba/' . $lomba->id_lomba . '/showMahasiswa') . '" class="btn btn-info btn-sm">Detail</a>',
                'rank' => $rank <= 5 ? $rank++ : null, // Hanya beri rank untuk 5 teratas
            ];
        });

        return response()->json(['data' => $data->values()]);
    }

    public function listInput(Request $request)
    {
        $user = auth()->user();
        $keyword = $request->input('keyword');
        $filterKategori = $request->input('kategori');
        $filterStatus = $request->input('status_verifikasi');

        $lomba = LombaModel::with(['kategoris', 'tingkatPrestasi'])
            ->where('added_by', $user->id_pengguna)
            ->when($filterKategori, function ($query, $filterKategori) {
                return $query->whereHas('kategoris', function ($q) use ($filterKategori) {
                    $q->where('kategori.id_kategori', $filterKategori);
                });
            })
            ->when($filterStatus !== null, function ($query) use ($filterStatus) {
                return $query->where('status_verifikasi', $filterStatus);
            })
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function ($q) use ($keyword) {
                    $q->where('nama_lomba', 'like', "%{$keyword}%")
                        ->orWhereHas('kategoris', fn($q2) => $q2->where('nama_kategori', 'like', "%{$keyword}%"))
                        ->orWhereHas('tingkatPrestasi', fn($q3) => $q3->where('nama_tingkat_prestasi', 'like', "%{$keyword}%"));
                });
            });

        return DataTables::of($lomba->get())
            ->addIndexColumn()
            ->addColumn('kategori', function ($l) {
                return $l->kategoris->pluck('nama_kategori')->implode(', ') ?: '-';
            })
            ->addColumn('nama_kategori', function ($l) {
                return $l->kategoris->pluck('nama_kategori')->implode(', ') ?: '-';
            })
            ->addColumn('tingkat', fn($l) => $l->tingkatPrestasi->nama_tingkat_prestasi ?? '-')
            ->addColumn('aksi', function ($l) {
                $btn = '<a href="' . url('/lomba/' . $l->id_lomba . '/showInput') . '" class="btn btn-info btn-sm">Detail</a>';
                $btn .= '<button onclick="modalAction(\'' . url('/lomba/' . $l->id_lomba . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/lomba/' . $l->id_lomba . '/confirm_delete') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show($id)
    {
        $data = LombaModel::with(['kategori', 'tingkatPrestasi', 'pengusul', 'periode'])->find($id);
        $activeMenu = 'lomba';
        $breadcrumb = (object)[
            'title' => 'Detail Lomba',
            'list' => ['Detail Lomba']
        ];
        return view('lomba.show', compact('data', 'breadcrumb'));
    }

    public function showMahasiswa($id)
    {
        $data = LombaModel::with(['kategori', 'tingkatPrestasi', 'pengusul', 'periode'])->find($id);
        $activeMenu = 'lomba';
        $breadcrumb = (object)[
            'title' => 'Detail Lomba',
            'list' => ['Detail Lomba']
        ];
        return view('lomba.showMahasiswa', compact('data', 'breadcrumb'));
    }

    public function showDosen($id)
    {
        $data = LombaModel::with(['kategori', 'tingkatPrestasi', 'pengusul', 'periode'])->find($id);
        $activeMenu = 'lomba';
        $breadcrumb = (object)[
            'title' => 'Detail Lomba',
            'list' => ['Detail Lomba']
        ];

        $mahasiswaList = MahasiswaModel::all();

        return view('lomba.showDosen', compact('data', 'breadcrumb', 'mahasiswaList'));
    }


    public function showInput($id)
    {
        $data = LombaModel::with(['kategori', 'tingkatPrestasi', 'pengusul', 'periode'])->find($id);
        $activeMenu = 'lomba';
        $breadcrumb = (object)[
            'title' => 'Detail Lomba',
            'list' => ['Detail Lomba']
        ];
        return view('lomba.showInput', compact('data', 'breadcrumb'));
    }

    public function setujui($id_lomba)
    {
        $lomba = LombaModel::findOrFail($id_lomba);
        $lomba->status_verifikasi = 1;
        $lomba->catatan_penolakan = null; // Kosongkan catatan penolakan jika disetujui
        $lomba->save();

        return redirect()->back()->with('success', 'Data Lomba berhasil disetujui!');
    }

    public function tolak(Request $request, $id_lomba)
    {
        $request->validate([
            'catatan_penolakan' => 'required|string'
        ]);

        $lomba = LombaModel::findOrFail($id_lomba);
        $lomba->status_verifikasi = 0;
        $lomba->catatan_penolakan = $request->catatan_penolakan;
        $lomba->save();

        return redirect()->back()->with('success', 'Data Lomba berhasil ditolak.');
    }

    public function create()
    {
        return view('lomba.create', [
            'id_kategori' => KategoriModel::all(),
            'id_tingkat_prestasi' => TingkatPrestasiModel::all(),
            'periode' => PeriodeModel::all(),
            'added_by' => PenggunaModel::all(),
            'role_pengusul' => RoleModel::all()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lomba' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'id_kategori' => 'required|array|max:3',
            'id_kategori.*' => 'exists:kategori,id_kategori',
            'id_tingkat_prestasi' => 'required|exists:tingkat_prestasi,id_tingkat_prestasi',
            'deskripsi' => 'required|string',
            'periode' => 'required|integer',
            'link_pendaftaran' => 'nullable|url',
            'tipe_lomba' => 'required|in:individu,tim',
            'biaya_pendaftaran' => 'required|boolean',
            'berhadiah' => 'required|boolean',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'deadline_pendaftaran' => 'required|date|before_or_equal:tanggal_mulai',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama_lomba.required' => 'Nama lomba wajib diisi.',
            'nama_lomba.max' => 'Nama lomba maksimal 255 karakter.',
            'penyelenggara.required' => 'Penyelenggara wajib diisi.',
            'penyelenggara.max' => 'Penyelenggara maksimal 255 karakter.',
            'id_kategori.required' => 'Kategori harus dipilih.',
            'id_kategori.array' => 'Kategori harus berupa array.',
            'id_kategori.max' => 'Maksimal pilih 3 kategori.',
            'id_kategori.*.exists' => 'Kategori tidak valid.',
            'id_tingkat_prestasi.required' => 'Tingkat prestasi harus dipilih.',
            'id_tingkat_prestasi.exists' => 'Tingkat prestasi tidak valid.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'periode.required' => 'Periode harus dipilih.',
            'periode.integer' => 'Format periode tidak valid.',
            'link_pendaftaran.url' => 'Link pendaftaran harus berupa URL yang valid.',
            'tipe_lomba.required' => 'Tipe lomba wajib diisi.',
            'tipe_lomba.in' => 'Tipe lomba harus individu atau tim.',
            'biaya_pendaftaran.required' => 'Silakan pilih apakah lomba ini berbayar atau tidak.',
            'biaya_pendaftaran.boolean' => 'Format biaya tidak valid.',
            'berhadiah.required' => 'Silakan pilih apakah lomba ini berhadiah atau tidak.',
            'berhadiah.boolean' => 'Format berhadiah tidak valid.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'deadline_pendaftaran.required' => 'Deadline pendaftaran wajib diisi.',
            'deadline_pendaftaran.date' => 'Format deadline tidak valid.',
            'deadline_pendaftaran.before_or_equal' => 'Deadline pendaftaran harus sebelum atau sama dengan tanggal mulai.',
            'foto.required' => 'Foto wajib diunggah.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpg, jpeg, atau png.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal. Silakan periksa kembali input Anda.',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!auth()->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Pengguna tidak terautentikasi.'
            ]);
        }

        $data = $request->only([
            'nama_lomba',
            'penyelenggara',
            'id_tingkat_prestasi',
            'deskripsi',
            'periode',
            'link_pendaftaran',
            'tipe_lomba',
            'biaya_pendaftaran',
            'berhadiah',
            'tanggal_mulai',
            'tanggal_selesai',
            'deadline_pendaftaran',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('foto_lomba', $filename, 'public');
            $data['foto'] = 'foto_lomba/' . $filename;
        }

        $data['added_by'] = auth()->user()->id_pengguna;
        $data['role_pengusul'] = auth()->user()->role_id;
        $data['status_verifikasi'] = auth()->user()->role_id == 1 ? true : null;

        $lomba = LombaModel::create($data);
        // Simpan relasi ke tabel pivot kategori_lomba_pivot
        $lomba->kategoris()->attach($request->id_kategori);


        return response()->json([
            'status' => true,
            'message' => 'Lomba berhasil disimpan.'
        ]);
    }


    public function edit($id_lomba)
    {
        $lomba = LombaModel::with(['kategoris', 'tingkatPrestasi', 'periode'])->find($id_lomba);

        if (!$lomba) {
            return response()->json([
                'status' => false,
                'message' => 'Data Lomba tidak ditemukan.'
            ]);
        }


        $id_kategori = KategoriModel::all();
        $id_tingkat_prestasi = TingkatPrestasiModel::all();
        $id_periode = PeriodeModel::all();


        return view('lomba.edit', compact('lomba', 'id_kategori', 'id_tingkat_prestasi', 'id_periode'));
    }

    public function update(Request $request, $id)
    {
        $lomba = LombaModel::find($id);

        if (!$lomba) {
            return response()->json([
                'status' => false,
                'message' => 'Data lomba tidak ditemukan.'
            ]);
        }

        if ($lomba->status_verifikasi == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Lomba sudah diverifikasi dan tidak bisa diedit.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'nama_lomba' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'id_kategori' => 'required|array|max:3',
            'id_kategori.*' => 'exists:kategori,id_kategori',
            'id_tingkat_prestasi' => 'required|exists:tingkat_prestasi,id_tingkat_prestasi',
            'deskripsi' => 'required|string',
            'periode' => 'required|exists:periode,id_periode',
            'link_pendaftaran' => 'nullable|url',
            'biaya_pendaftaran' => 'required|in:0,1',
            'berhadiah' => 'required|in:0,1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'deadline_pendaftaran' => 'required|date|before_or_equal:tanggal_mulai',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama_lomba.required' => 'Nama lomba wajib diisi.',
            'nama_lomba.max' => 'Nama lomba maksimal 255 karakter.',
            'penyelenggara.required' => 'Penyelenggara wajib diisi.',
            'penyelenggara.max' => 'Penyelenggara maksimal 255 karakter.',
            'id_kategori.required' => 'Kategori harus dipilih.',
            'id_kategori.exists' => 'Kategori tidak valid.',
            'id_tingkat_prestasi.required' => 'Tingkat prestasi harus dipilih.',
            'id_tingkat_prestasi.exists' => 'Tingkat prestasi tidak valid.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'periode.required' => 'Periode harus dipilih.',
            'periode.exists' => 'Periode tidak valid.',
            'link_pendaftaran.url' => 'Link pendaftaran harus berupa URL yang valid.',
            'biaya_pendaftaran.required' => 'Silakan pilih apakah lomba ini berbayar atau tidak.',
            'biaya_pendaftaran.in' => 'Format biaya tidak valid.',
            'berhadiah.required' => 'Silakan pilih apakah lomba ini berhadiah atau tidak.',
            'berhadiah.in' => 'Format berhadiah tidak valid.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'deadline_pendaftaran.required' => 'Deadline pendaftaran wajib diisi.',
            'deadline_pendaftaran.date' => 'Format deadline tidak valid.',
            'deadline_pendaftaran.before_or_equal' => 'Deadline pendaftaran harus sebelum atau sama dengan tanggal mulai.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpg, jpeg, atau png.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal. Silahkan cek kembali inputan anda',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update data lomba dari request
        $lomba->nama_lomba = $request->nama_lomba;
        $lomba->penyelenggara = $request->penyelenggara;
        $lomba->id_tingkat_prestasi = $request->id_tingkat_prestasi;
        $lomba->deskripsi = $request->deskripsi;
        $lomba->periode = $request->periode;
        $lomba->link_pendaftaran = $request->link_pendaftaran;
        $lomba->biaya_pendaftaran = $request->biaya_pendaftaran;
        $lomba->berhadiah = $request->berhadiah;
        $lomba->tanggal_mulai = $request->tanggal_mulai;
        $lomba->tanggal_selesai = $request->tanggal_selesai;
        $lomba->deadline_pendaftaran = $request->deadline_pendaftaran;

        // Handle upload foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Hapus foto lama jika ada
            if ($lomba->foto && Storage::disk('public')->exists($lomba->foto)) {
                Storage::disk('public')->delete($lomba->foto);
            }

            // Simpan foto baru
            $file->storeAs('foto_lomba', $filename, 'public');

            // Simpan path relatif ke database
            $lomba->foto = 'foto_lomba/' . $filename;
        }

        $lomba->save();
        $lomba->kategoris()->sync($request->id_kategori);

        return response()->json([
            'status' => true,
            'message' => 'Data lomba berhasil diperbarui.'
        ]);
    }

    public function confirm_delete($id)
    {
        $data = LombaModel::with(['kategoris', 'tingkatPrestasi', 'periode'])->findOrFail($id);
        return view('lomba.delete', ['data' => $data]);
    }

    public function delete(Request $request, $id)
    {
        $lomba = LombaModel::find($id);
        if ($lomba) {
            $lomba->delete();
            return response()->json([
                'status' => true,
                'message' => 'Lomba berhasil dihapus.'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Lomba tidak ditemukan.'
        ]);
    }
}
