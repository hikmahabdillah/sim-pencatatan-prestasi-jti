<?php

namespace App\Http\Controllers;

use App\Models\AnggotaPrestasiModel;
use App\Models\LombaModel;
use App\Models\PrestasiMahasiswaModel;
use App\Models\PenggunaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\DatabaseNotification;

class DashboardController extends Controller
{
    //text
    private function totalPrestasiMahasiswa()
    {
        $auth = auth()->user();

        if ($auth->role_id == 3) {
            // Mahasiswa
            $jmlPrestasi = AnggotaPrestasiModel::where('id_mahasiswa', $auth->mahasiswa->id_mahasiswa)
                ->whereHas('prestasi', function ($query) {
                    $query->where('status_verifikasi', 1);
                })
                ->count();

            return $jmlPrestasi;
        } elseif ($auth->role_id == 2) {
            // Dosen Pembimbing
            $jmlPrestasi = PrestasiMahasiswaModel::where('id_dospem', $auth->dosen->id_dospem)
                ->where('status_verifikasi', 1)
                ->count();

            return $jmlPrestasi;
        } elseif ($auth->role_id == 1) {
            // Admin
            return PrestasiMahasiswaModel::where('status_verifikasi', 1)->count();
        }

        return 0;
    }


    // diagram
    // jumlah prestasi per kategori
    private function jumlahPrestasiByKategori()
    {
        $data = DB::table('kategori')
            ->leftJoin('prestasi_mahasiswa', 'kategori.id_kategori', '=', 'prestasi_mahasiswa.id_kategori')
            ->select('kategori.nama_kategori', DB::raw('COUNT(prestasi_mahasiswa.id_prestasi) as jumlah'))
            ->groupBy('kategori.id_kategori', 'kategori.nama_kategori') //Mengelompokkan hasil berdasarkan id_kategori dan nama_kategori
            ->where('prestasi_mahasiswa.status_verifikasi', 1)
            ->get();

        return $data;
    }


    // diagram
    // distribusi tingkat prestasi yang dicapai
    private function tingkatPrestasiDicapai()
    {
        $data = DB::table('tingkat_prestasi')
            ->leftJoin('prestasi_mahasiswa', 'tingkat_prestasi.id_tingkat_prestasi', '=', 'prestasi_mahasiswa.id_tingkat_prestasi')
            ->select('tingkat_prestasi.nama_tingkat_prestasi', DB::raw('COUNT(prestasi_mahasiswa.id_prestasi) as jumlah'))
            ->groupBy('tingkat_prestasi.id_tingkat_prestasi', 'tingkat_prestasi.nama_tingkat_prestasi')
            ->where('prestasi_mahasiswa.status_verifikasi', 1)
            ->get();

        return $data;
    }


    // text
    private function totalLombaAktif()
    {
        $lomba = LombaModel::all();
        $today = Carbon::now()->toDateString();
        $auth = auth()->user();

        if ($auth->role_id == 1) {
            // lomba yang sedang berlangsung
            $jmlLomba = $lomba->where('tanggal_mulai', '<=', $today)
                ->where('tanggal_selesai', '>=', $today)
                ->count();
            return $jmlLomba;
        } else if ($auth->role_id == 2 || $auth->role_id == 3) {
            // lomba yang aktif
            return $lomba->where('deadline_pendaftaran', '>=', $today)->count();
        }
    }

    //diagram
    // jumlah lomba per kategori
    private function LombaByKategori()
    {
        $jumlahPerKategori = DB::table('kategori_lomba_pivot')
            ->join('kategori', 'kategori_lomba_pivot.id_kategori', '=', 'kategori.id_kategori')
            ->select('kategori.nama_kategori', DB::raw('COUNT(kategori_lomba_pivot.id_lomba) as total'))
            ->groupBy('kategori.nama_kategori')
            ->get();

        // Bentuk hasil sesuai struktur yang kamu inginkan
        $hasil = $jumlahPerKategori->map(function ($item) {
            return [
                'kategori' => $item->nama_kategori,
                'jumlah' => $item->total
            ];
        });

        return $hasil;
    }


    // diagram 
    private function prestasiMahasiswaPerSemester()
    {
        $prestasi = DB::table('periode as p')
            ->leftJoin('prestasi_mahasiswa as pm', 'p.id_periode', '=', 'pm.id_periode')
            ->select(
                'p.id_periode',
                'p.tahun_ajaran',
                DB::raw("COUNT(CASE WHEN pm.status_verifikasi = 1 THEN pm.id_prestasi END) as total")
            )
            ->groupBy('p.id_periode', 'p.tahun_ajaran')
            ->orderBy('p.id_periode', 'asc')
            ->get();

        $hasil = $prestasi->map(function ($item) {
            return [
                'periode' => $item->tahun_ajaran,
                'jumlah'  => $item->total
            ];
        });

        return $hasil;
    }

    // diagram
    private function rankMahasiswaByPrestasi()
    {
        $ranking = DB::table('prestasi_mahasiswa')
            ->join('anggota_prestasi', 'prestasi_mahasiswa.id_prestasi', '=', 'anggota_prestasi.id_prestasi')
            ->join('mahasiswa', 'anggota_prestasi.id_mahasiswa', '=', 'mahasiswa.id_mahasiswa')
            ->join('pengguna', 'mahasiswa.id_pengguna', '=', 'pengguna.id_pengguna')
            ->selectRaw('
            mahasiswa.id_mahasiswa,
            mahasiswa.nama,
            pengguna.foto,
            COUNT(DISTINCT prestasi_mahasiswa.id_prestasi) as total_prestasi
        ')
            ->where('prestasi_mahasiswa.status_verifikasi', 1)
            ->groupBy('mahasiswa.id_mahasiswa', 'mahasiswa.nama', 'pengguna.foto')
            ->orderByDesc('total_prestasi')
            ->get();

        return $ranking->map(function ($item) {
            return [
                'nama'   => $item->nama,
                'foto'   => $item->foto ?? null,
                'jumlah' => $item->total_prestasi
            ];
        });
    }
    public function index()
    {
        $activeMenu = 'dashboard'; // digunakan untuk menandai menu aktif di sidebar
        $breadcrumb = (object)[
            'title' => 'Dashboard', // untuk title halaman
            'list'  => ['Dashboard'] // untuk breadcrumb
        ];
        $jmlPrestasi = $this->totalPrestasiMahasiswa();
        $jmlLomba = $this->totalLombaAktif();
        $jumlahPrestasiByKategori = $this->jumlahPrestasiByKategori();
        $tingkatPrestasiDicapai = $this->tingkatPrestasiDicapai();
        $LombaByKategori = $this->LombaByKategori();
        $prestasiMahasiswaPerSemester = $this->prestasiMahasiswaPerSemester();
        $rankMahasiswaByPrestasi = $this->rankMahasiswaByPrestasi();

        // Notif
        $allNotifikasi = auth()->user()->notifications->latest()->limit(10)->get();

        return view('dashboard', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'jmlPrestasi' => $jmlPrestasi,
            'jmlLomba' => $jmlLomba,
            'jumlahPrestasiByKategori' => $jumlahPrestasiByKategori,
            'tingkatPrestasiDicapai' => $tingkatPrestasiDicapai,
            'lombaByKategori' => $LombaByKategori,
            'prestasiMahasiswaPerSemester' => $prestasiMahasiswaPerSemester,
            'rankMahasiswaByPrestasi' => $rankMahasiswaByPrestasi,
            'navbarNotifications' => $allNotifikasi,
        ]);
    }
}
