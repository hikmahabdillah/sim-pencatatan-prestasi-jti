<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\PrestasiMahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //text
    private function totalPrestasiMahasiswa()
    {
        $prestasi = PrestasiMahasiswaModel::all();
        $auth = auth()->user();

        if ($auth->role_id == 3) {
            $jmlPrestasi = $prestasi->where('id_mahasiswa', $auth->mahasiswa->id_mahasiswa)->count();
            return $jmlPrestasi;
        } else if ($auth->role_id == 2) {
            $jmlPrestasi = $prestasi->where('id_dospem', $auth->dosen->id_dospem)->count();
            return $jmlPrestasi;
        } else if ($auth->role_id == 1) {
            $jmlPrestasi = $prestasi->count();
            return $jmlPrestasi;
        }
    }

    // diagram
    private function jumlahPrestasiByKategori()
    {
        $data = DB::table('kategori')
            ->leftJoin('prestasi_mahasiswa', 'kategori.id_kategori', '=', 'prestasi_mahasiswa.id_kategori')
            ->select('kategori.nama_kategori', DB::raw('COUNT(prestasi_mahasiswa.id_prestasi) as jumlah'))
            ->groupBy('kategori.id_kategori', 'kategori.nama_kategori')
            ->get();

        return $data;
    }


    // diagram
    private function tingkatPrestasiDicapai()
    {
        $data = DB::table('tingkat_prestasi')
            ->leftJoin('prestasi_mahasiswa', 'tingkat_prestasi.id_tingkat_prestasi', '=', 'prestasi_mahasiswa.id_tingkat_prestasi')
            ->select('tingkat_prestasi.nama_tingkat_prestasi', DB::raw('COUNT(prestasi_mahasiswa.id_prestasi) as jumlah'))
            ->groupBy('tingkat_prestasi.id_tingkat_prestasi', 'tingkat_prestasi.nama_tingkat_prestasi')
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
    private function LombaByKategori()
    {
        $jumlahPerKategori = LombaModel::select('id_kategori')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('id_kategori')
            ->with('kategori')
            ->get();

        $hasil = $jumlahPerKategori->map(function ($item) {
            return [
                'kategori' => $item->kategori->nama_kategori ?? 'Tidak diketahui',
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
                DB::raw('COUNT(pm.id_prestasi) as total')
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
        $ranking = PrestasiMahasiswaModel::selectRaw('
        prestasi_mahasiswa.id_mahasiswa,
        COUNT(prestasi_mahasiswa.id_prestasi) as total_prestasi
    ')
            ->groupBy('prestasi_mahasiswa.id_mahasiswa')
            ->with(['mahasiswa.pengguna']) // eager load relasi mahasiswa dan pengguna untuk ambil nama + foto
            ->orderByDesc('total_prestasi')
            ->get();

        $hasil = $ranking->map(function ($item) {
            return [
                'nama'   => $item->mahasiswa->nama,
                'foto'   => $item->mahasiswa->pengguna->foto ?? null,
                'jumlah' => $item->total_prestasi
            ];
        });

        return $hasil;
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
        ]);
    }
}
