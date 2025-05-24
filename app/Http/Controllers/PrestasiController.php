<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaModel;
use App\Models\PrestasiMahasiswaModel;
use Illuminate\Http\Request;

class PrestasiController extends Controller
{
    public function getPrestasiMahasiswa($id)
    {
        $breadcrumb = (object)[
            'title' => 'Prestasi Mahasiswa',
            'list'  => ['Mahasiswa', 'Prestasi Mahasiswa']
        ];

        // Mengambil data prestasi mahasiswa berdasarkan id
        $prestasi = PrestasiMahasiswaModel::where('id_mahasiswa', $id)->get();
        if (!$prestasi) {
            return redirect('/mahasiswa/' . $id . '/prestasi')->with('error', 'Data mahasiswa tidak ditemukan');
        }

        $prestasi = PrestasiMahasiswaModel::with([
            'tingkatPrestasi',
            'kategori',
            'periode',
            'dosenPembimbing'
        ])
            ->where('id_mahasiswa', $id)
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
            'dosenPembimbing'
        ])
            ->where('id_prestasi', $id)
            ->first();

        // if ($prestasi) {
        //     return redirect('/mahasiswa/' . $prestasi->id_mahasiswa . '/prestasi')->with('error', 'Data prestasi tidak ditemukan');
        // }

        return view('prestasi.show', [
            'prestasi' => $prestasi,
            'breadcrumb' => $breadcrumb,
        ]);
    }
}
