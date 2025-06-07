<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaModel;
use App\Models\LombaModel;
use App\Models\MinatBakatPenggunaModel;
use App\Models\RekomendasiLombaModel;
use App\Models\KategoriModel;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class RekomendasiLombaController extends Controller
{
    public function hitungRekomendasiDenganStep($idMahasiswa)
    {
        $mahasiswa = MahasiswaModel::with('kategori')->findOrFail($idMahasiswa);

        // Ambil minat mahasiswa
        $minatMahasiswa = MinatBakatPenggunaModel::where('id_pengguna', $mahasiswa->id_pengguna)
            ->pluck('id_kategori')
            ->toArray();

        $lombaList = LombaModel::all();
        $steps = [];
        $dataSkor = [];

        foreach ($lombaList as $lomba) {
            $steps[] = "Hitung kriteria untuk lomba: {$lomba->nama}";

            // C1: Kesesuaian minat
            $c1 = in_array($lomba->id_kategori, $minatMahasiswa) ? 3 : 1;
            $steps[] = "C1 (Kesesuaian Minat): {$c1}";

            //  C2: Jumlah prestasi sesuai kategori
            $jumlahPrestasi = $mahasiswa->prestasi()
                ->where('id_kategori', $lomba->id_kategori)
                ->count();

            if ($jumlahPrestasi == 0) {
                $c2 = 1;
            } elseif ($jumlahPrestasi <= 3) {
                $c2 = 2;
            } elseif ($jumlahPrestasi <= 7) {
                $c2 = 3;
            } elseif ($jumlahPrestasi <= 10) {
                $c2 = 4;
            } else {
                $c2 = 5;
            }
            $steps[] = "C2 (Jumlah Prestasi): {$jumlahPrestasi} -> skor {$c2}";

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


            //  C5: Biaya pendaftaran (gratis = 3, berbayar = 1)
            $c5 = $lomba->biaya_pendaftaran == 0 ? 3 : 1;
            $steps[] = "C5 (Biaya Pendaftaran): " . ($lomba->biaya == 0 ? "Gratis" : "Berbayar") . " -> skor {$c5}";

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



        //  Return ke view
        return view('rekomendasi.detail', [
            'steps' => $steps,
            'mahasiswa' => $mahasiswa,
            'hasil' => $normalisasi,
            'lombaMap' => $lombaMap,
            'kategoriMinat' => $kategoriMinat,
        ]);
    }
}