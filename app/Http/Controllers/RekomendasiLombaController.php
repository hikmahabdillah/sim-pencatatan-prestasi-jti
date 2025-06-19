<?php

namespace App\Http\Controllers;

use App\Models\DosenPembimbingModel;
use App\Models\MahasiswaModel;
use App\Models\LombaModel;
use App\Models\MinatBakatPenggunaModel;
use App\Models\RekomendasiLombaModel;
use App\Models\KategoriModel;
use App\Models\PenggunaModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Notifications\RekomendasiLombaBaru;
use App\Notifications\AdminRekomendasiNotification;

class RekomendasiLombaController extends Controller
{
    public function prosesRekomendasi($idMahasiswa): array
    {
        $mahasiswa = MahasiswaModel::with('kategori')->findOrFail($idMahasiswa);

        // Ambil minat mahasiswa
        $minatMahasiswa = MinatBakatPenggunaModel::where('id_pengguna', $mahasiswa->id_pengguna)
            ->pluck('id_kategori')
            ->toArray();

        $lombaList = LombaModel::with(['kategoris', 'tingkatPrestasi'])
            ->whereDate('deadline_pendaftaran', '>=', now()->toDateString())
            ->where('status_verifikasi', 1)
            ->get();

        // Ambil ID lomba yang sudah pernah direkomendasikan sebelumnya
        $existingLombaIds = RekomendasiLombaModel::where('id_mahasiswa', $idMahasiswa)
            ->pluck('id_lomba')
            ->toArray();

        $steps = [];
        $dataSkor = []; 

        foreach ($lombaList as $lomba) {
            $steps[] = "Hitung kriteria untuk lomba: {$lomba->nama}";

            // C1: Kesesuaian minat (berdasarkan pivot kategori)
            $kategoriLombaIds = $lomba->kategoris->pluck('id_kategori')->toArray();
            $jumlahKategoriCocok = count(array_intersect($kategoriLombaIds, $minatMahasiswa));

            if ($jumlahKategoriCocok == 0) {
                $c1 = 1;
            } elseif ($jumlahKategoriCocok == 1) {
                $c1 = 2;
            } else {
                $c1 = 3;
            }
            $steps[] = "C1 (Kesesuaian Minat): {$jumlahKategoriCocok} kategori cocok -> skor {$c1}";

            // C2: Jumlah prestasi sesuai kategori (cek jika ada prestasi mahasiswa sesuai kategori pivot lomba)
            // Ambil semua kategori dari lomba 
            $kategoriLombaIds = $lomba->kategoris->pluck('id_kategori')->toArray();

            // Ambil kategori unik dari prestasi mahasiswa
            $kategoriPrestasiMahasiswa = $mahasiswa->prestasi()
                ->pluck('id_kategori')
                ->unique();

            // Hitung berapa banyak kategori prestasi yang cocok dengan kategori lomba
            $jumlahMatchKategori = $kategoriPrestasiMahasiswa
                ->intersect($kategoriLombaIds)
                ->count();

            // Skor C2 berdasarkan jumlah match kategori
            if ($jumlahMatchKategori == 0) {
                $c2 = 1;
            } elseif ($jumlahMatchKategori <= 3) {
                $c2 = 2;
            } elseif ($jumlahMatchKategori <= 7) {
                $c2 = 3;
            } elseif ($jumlahMatchKategori <= 10) {
                $c2 = 4;
            } else {
                $c2 = 5;
            }

            $steps[] = "C2 (Jumlah Kategori Prestasi yang Sesuai): {$jumlahMatchKategori} -> skor {$c2}";

            //  C3: Tingkat lomba
            $tingkat = strtolower($lomba->tingkatPrestasi->nama_tingkat_prestasi);

            if ($tingkat == 'kampus') {
                $c3 = 1;
            } elseif ($tingkat == 'kota') {
                $c3 = 2;
            } elseif ($tingkat == 'provinsi') {
                $c3 = 3;
            } elseif ($tingkat == 'nasional') {
                $c3 = 4;
            } elseif ($tingkat == 'internasional') {
                $c3 = 5;
            } else {
                $c3 = 1; // default skor
            }

            $steps[] = "C3 (Tingkat Lomba): {$tingkat} -> skor {$c3}";


            //  C4: Durasi pendaftaran
            $now = now()->startOfDay();
            $deadline = Carbon::parse($lomba->deadline_pendaftaran)->startOfDay();
            $durasi = $now->diffInDays($deadline, false);

            $c4 = match (true) {
                $durasi <= 7 => 1,
                $durasi <= 14 => 2,
                $durasi <= 21 => 3,
                $durasi <= 30 => 4,
                default => 5,
            };

            $steps[] = "C4 (Durasi Pendaftaran): {$durasi} hari -> skor {$c4}";


            //  C5: Biaya pendaftaran (gratis = 1, berbayar = 3)
            $c5 = $lomba->biaya_pendaftaran == 0 ? 1 : 3;
            $steps[] = "C5 (Biaya Pendaftaran): " . ($lomba->biaya_pendaftaran == 0 ? "Gratis" : "Berbayar") . " -> skor {$c5}";

            //  C6: Ada benefit atau tidak
            $c6 = $lomba->berhadiah == 1 ? 3 : 1;
            $steps[] = "C6 (Benefit Lomba): " . ($lomba->berhadiah ? "Ada" : "Tidak Ada") . " -> skor {$c6}";

            $dataSkor[] = [
                'id_lomba' => $lomba->id_lomba,
                'c1' => $c1,
                'c2' => $c2,
                'c3' => $c3,
                'c4' => $c4,
                'c5' => $c5,
                'c6' => $c6,
            ];

            $steps[] = "-------------------------------------------";
        }

        //  Normalisasi
        $steps[] = "Langkah Normalisasi:";

        // Hitung pembagi untuk tiap kriteria (akar jumlah kuadrat)
        $pembagi = [];
        foreach (['c1', 'c2', 'c3', 'c4', 'c5', 'c6'] as $k) {
            $pembagi[$k] = sqrt(array_sum(array_map(fn($d) => pow($d[$k], 2), $dataSkor)));
            $steps[] = "Pembagi untuk {$k}: {$pembagi[$k]}";
        }

        // Bobot masing-masing kriteria
        $bobot = [
            'c1' => 0.25,  // Minat
            'c2' => 0.20,  // Prestasi
            'c3' => 0.20,  // Tingkat
            'c4' => 0.15,  // Durasi 
            'c5' => 0.10,  // Biaya (Cost)
            'c6' => 0.10,  // Benefit tambahan
        ];

        $normalisasi = [];

        foreach ($dataSkor as $d) {
            $n = [
                'id_lomba' => $d['id_lomba'],
            ];

            $steps[] = "Normalisasi untuk lomba {$d['id_lomba']}:";

            // Normalisasi dan pembobotan
            foreach (['c1', 'c2', 'c3', 'c4', 'c5', 'c6'] as $k) {
                $n[$k] = $d[$k]; // simpan nilai asli
                $n["n_{$k}"] = $d[$k] / $pembagi[$k]; // normalisasi
                $n["w_{$k}"] = $n["n_{$k}"] * $bobot[$k]; // normalisasi terbobot

                $steps[] = "n_{$k} = {$d[$k]} / {$pembagi[$k]} = {$n["n_{$k}"]}";
                $steps[] = "w_{$k} = n_{$k} * bobot = {$n["n_{$k}"]} * {$bobot[$k]} = {$n["w_{$k}"]}";
            }

            // Hitung MOORA
            $n['benefit'] = $n['w_c1'] + $n['w_c2'] + $n['w_c3'] + $n['w_c4']+ $n['w_c6'];
            $n['cost'] = $n['w_c5'];
            $n['skor_moora'] = $n['benefit'] - $n['cost'];

            $steps[] = "Benefit = w_c1 + w_c2 + w_c3 + w_c4 + w_c6 = {$n['w_c1']} + {$n['w_c2']} + {$n['w_c3']} + {$n['w_c4']} + {$n['w_c6']} = {$n['benefit']}";
            $steps[] = "Cost = w_c5  = {$n['w_c5']} = {$n['cost']}";
            $steps[] = "Skor MOORA = Benefit - Cost = {$n['benefit']} - {$n['cost']} = {$n['skor_moora']}";
            $steps[] = "-------------------------------------------";

            $normalisasi[] = $n;
        }
        // Cari hanya yang belum pernah dikirimi notifikasi
        $lombaBaru = RekomendasiLombaModel::where('id_mahasiswa', $idMahasiswa)
            ->where('notifikasi_terkirim', false)
            ->pluck('id_lomba')
            ->toArray();


        //  Simpan ke database
        foreach ($normalisasi as $r) {
            RekomendasiLombaModel::updateOrCreate(
                ['id_mahasiswa' => $idMahasiswa, 'id_lomba' => $r['id_lomba']],
                [
                    'c1_kesesuaian_minat' => $r['c1'],
                    'c2_jumlah_prestasi_sesuai' => $r['c2'],
                    'c3_tingkat_lomba' => $r['c3'],
                    'c4_durasi_pendaftaran' => $r['c4'],
                    'c5_biaya_pendaftaran' => $r['c5'],
                    'c6_benefit_lomba' => $r['c6'],
                    'n_c1' => $r['n_c1'],
                    'n_c2' => $r['n_c2'],
                    'n_c3' => $r['n_c3'],
                    'n_c4' => $r['n_c4'],
                    'n_c5' => $r['n_c5'],
                    'n_c6' => $r['n_c6'],
                    'skor_moora' => $r['skor_moora'],
                ]
            );
        }
        $lombaMap = LombaModel::pluck('nama_lomba', 'id_lomba');
        $kategoriMinat = KategoriModel::whereIn('id_kategori', $minatMahasiswa)->get();

        // Kirim notifikasi ke mahasiswa
        if (!empty($lombaBaru)) {
            $namaLombaBaru = LombaModel::whereIn('id_lomba', $lombaBaru)->pluck('nama_lomba')->implode(', ');

            $pengusul = auth()->user()->name ?? 'Sistem';
            $mahasiswa->pengguna->notify(new RekomendasiLombaBaru($pengusul, $namaLombaBaru));

            // Update semua yang sudah dikirimi notif
            RekomendasiLombaModel::where('id_mahasiswa', $idMahasiswa)
                ->whereIn('id_lomba', $lombaBaru)
                ->update(['notifikasi_terkirim' => true]);
        }

        //  Return ke view
        return [
            'steps' => $steps,
            'mahasiswa' => $mahasiswa,
            'hasil' => $normalisasi,
            'lombaMap' => $lombaMap,
            'kategoriMinat' => $kategoriMinat,
        ];
    }

    public function hitungRekomendasiDenganStep($idMahasiswa, Request $request = null)
    {
        $hasil = $this->prosesRekomendasi($idMahasiswa);
    
        return view('rekomendasi.detail', [
            'steps' => $hasil['steps'],
            'mahasiswa' => $hasil['mahasiswa'],
            'hasil' => $hasil['hasil'],
            'lombaMap' => $hasil['lombaMap'],
            'kategoriMinat' => $hasil['kategoriMinat'],
        ]);
    }

    public function index($id)
    {
        $lomba = LombaModel::with('kategoris', 'tingkatPrestasi')->findOrFail($id);

        $kategoriIds = $lomba->kategoris->pluck('id_kategori')->toArray();

        $dospemList = PenggunaModel::where('role_id', 2)
            ->whereHas('minatBakat', function ($q) use ($kategoriIds) {
                $q->whereIn('minat_bakat_pengguna.id_kategori', $kategoriIds);
            })
            ->get();

        $rekomendasi = RekomendasiLombaModel::with('mahasiswa.kategori', 'mahasiswa.prodi')
            ->where('id_lomba', $id)
            ->orderByDesc('skor_moora')
            ->take(5)
            ->get();

        // Ambil id_dospem dari salah satu rekomendasi jika sudah ada
        $dospemTerpilih = RekomendasiLombaModel::where('id_lomba', $id)
            ->whereNotNull('id_dospem')
            ->value('id_dospem');

        // Convert ke id_pengguna agar cocok dengan value <select>
        $idPenggunaDospem = null;
        if ($dospemTerpilih) {
            $idPenggunaDospem = \App\Models\DosenPembimbingModel::where('id_dospem', $dospemTerpilih)->value('id_pengguna');
        }

        return view('rekomendasi.mahasiswa', compact(
            'lomba',
            'rekomendasi',
            'dospemList'
        ))->with([
            'dospemTerpilih' => $idPenggunaDospem, 
            'empty' => $rekomendasi->isEmpty(),
            'message' => 'Belum ada mahasiswa yang direkomendasikan.'
        ]);
    }

    public function topMahasiswaLomba($idLomba)
    {
        // Validasi ID lomba
        if (!is_numeric($idLomba)) {
            abort(404, 'Lomba tidak valid');
        }

        // Ambil data lomba dengan eager loading
        $lomba = LombaModel::with(['kategoris', 'tingkatPrestasi'])
            ->findOrFail($idLomba);

        $rekomendasi = RekomendasiLombaModel::with([
            'mahasiswa.kategori',       // Minat bakat dari pivot
            'mahasiswa.prestasi'
        ])
            ->where('id_lomba', $idLomba)
            ->orderByDesc('skor_moora')
            ->limit(5)
            ->get();


        // Beri pesan jika tidak ada rekomendasi
        if ($rekomendasi->isEmpty()) {
            return view('rekomendasi.mahasiswa', [
                'empty' => true,
                'lomba' => $lomba,
                'message' => 'Belum ada rekomendasi mahasiswa untuk lomba ini'
            ]);
        }

        $dospem = DosenPembimbingModel::whereHas('pengguna', function ($query) {
            $query->where('status_aktif', 1);
        })
            ->with('pengguna')
            ->orderBy('nama')
            ->first(); // hanya ambil satu

        return view('rekomendasi.mahasiswa', [
            'rekomendasi' => $rekomendasi,
            'lomba' => $lomba,
            'dospemList' => $dospem,
            'empty' => false
        ]);
    }

   public function simpanDospem(Request $request)
{
    $request->validate([
        'id_lomba' => 'required|exists:lomba,id_lomba',
        'id_dospem' => 'required|exists:pengguna,id_pengguna', // Tetap validasi input awal
        'id_pengusul' => 'required|exists:pengguna,id_pengguna',
    ]);

    // Ambil id_dospem dari id_pengguna
    $dospem = \App\Models\DosenPembimbingModel::where('id_pengguna', $request->id_dospem)->first();

    if (!$dospem) {
        return back()->withErrors(['id_dospem' => 'Dosen pembimbing tidak ditemukan.']);
    }

    RekomendasiLombaModel::where('id_lomba', $request->id_lomba)
        ->update([
            'id_dospem' => $dospem->id_dospem, // ← gunakan id_dospem yang valid
            'id_pengusul' => $request->id_pengusul,
            'role_pengusul' => auth()->user()->role_id ?? 1,
            'tanggal_rekomendasi' => now(),
        ]);

        $mahasiswaList = RekomendasiLombaModel::where('id_lomba', $request->id_lomba)->get();

        foreach ($mahasiswaList as $rekom) {
            $mahasiswaModel = \App\Models\MahasiswaModel::find($rekom->id_mahasiswa);

            if ($mahasiswaModel && $mahasiswaModel->id_pengguna) {
                $userMahasiswa = \App\Models\PenggunaModel::find($mahasiswaModel->id_pengguna);

                if ($userMahasiswa) {
                    $userMahasiswa->notify(new AdminRekomendasiNotification(
                        $request->id_lomba,
                        $dospem->nama ?? 'Dosen Pembimbing'
                    ));
                }
            }
        }

        return back()->with('success', 'Dosen pembimbing berhasil ditetapkan untuk semua mahasiswa.');
}


    public function rekombyDosen(Request $request)
    {
        // Debug: Cek apakah data diterima
        Log::info('Rekomendasi Request Data:', $request->all());

        try {
            $request->validate([
                'id_lomba' => 'required|exists:lomba,id_lomba',
                'id_mahasiswa' => 'required|array|min:1',
                'id_mahasiswa.*' => 'exists:mahasiswa,id_mahasiswa',
            ]);

            $userId = auth()->user()->id_pengguna;
            $userRole = auth()->user()->role_id;
            $now = now();

            $successCount = 0;
            $alreadyExists = 0;

            foreach ($request->id_mahasiswa as $idMahasiswa) {

                // Cek apakah rekomendasi sudah ada
                $exists = RekomendasiLombaModel::where('id_lomba', $request->id_lomba)
                    ->where('id_mahasiswa', $idMahasiswa)
                    ->exists();

                if (!$exists) {

                    $created = RekomendasiLombaModel::create([
                        'id_lomba' => $request->id_lomba,
                        'id_mahasiswa' => $idMahasiswa,
                        'id_pengusul' => $userId,
                        'role_pengusul' => $userRole,
                        'tanggal_rekomendasi' => $now,
                    ]);

                    if ($created) {
                        $successCount++;
                        Log::info("Successfully created recommendation ID: " . $created->id);

                        // Ambil model mahasiswa dan penggunanya
                        $mahasiswa = \App\Models\MahasiswaModel::with('pengguna')->find($idMahasiswa);

                        if ($mahasiswa && $mahasiswa->pengguna) {
                            $mahasiswa->pengguna->notify(new \App\Notifications\RekomendasiLombaDospem(
                                $request->id_lomba,
                                auth()->user()->dosen->nama ?? auth()->user()->username // atau nama lengkap dosennya jika ada field-nya
                            ));
                        } else {
                            Log::warning("Pengguna untuk mahasiswa ID $idMahasiswa tidak ditemukan.");
                        }
                    } else {
                        Log::error("Failed to create recommendation for mahasiswa: $idMahasiswa");
                    }
                } else {
                    $alreadyExists++;
                    Log::info("Recommendation already exists for mahasiswa: $idMahasiswa");
                }
            }

            // Debug: Cek hasil akhir
            Log::info("Final results - Success: $successCount, Already exists: $alreadyExists");

            if ($successCount > 0) {
                return redirect()->back()->with('success', "Berhasil merekomendasikan lomba ke $successCount mahasiswa!");
            } elseif ($alreadyExists > 0) {
                return redirect()->back()->with('info', 'Semua mahasiswa yang dipilih sudah pernah direkomendasikan.');
            } else {
                return redirect()->back()->with('error', 'Tidak ada mahasiswa yang berhasil direkomendasikan.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            return redirect()->back()->with('error', 'Data tidak valid: ' . implode(', ', $e->validator->errors()->all()));
        } catch (\Exception $e) {
            Log::error('General Error in rekombyDosen:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}