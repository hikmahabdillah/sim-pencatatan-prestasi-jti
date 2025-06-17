<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaModel;
use App\Models\PrestasiMahasiswaModel;
use App\Models\ProdiModel;
use App\Models\PeriodeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Services\ExportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;


class LaporanPrestasiController extends Controller
{
    /**
     * Display main report page with two options
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Laporan Prestasi',
            'list' => ['Laporan Prestasi']
        ];

        return view('laporanprestasi.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => 'laporan-prestasi'
        ]);
    }

    /**
     * Show student list for report (A.a)
     */
    public function mahasiswa()
    {
        $breadcrumb = (object) [
            'title' => 'Laporan Berdasarkan Mahasiswa',
            'list' => ['Laporan Prestasi', 'Mahasiswa']
        ];

        $prodi = ProdiModel::all();

        return view('laporanprestasi.mahasiswa', [
            'breadcrumb' => $breadcrumb,
            'prodi' => $prodi,
            'activeMenu' => 'laporan-prestasi'
        ]);
    }

    /**
     * Get student data for datatable (A.a)
     */
    public function listMahasiswa(Request $request)
    {
        $mahasiswa = MahasiswaModel::with(['prodi', 'prestasi'])
            ->when($request->prodi, function ($query) use ($request) {
                return $query->where('id_prodi', $request->prodi);
            })
            ->when($request->angkatan, function ($query) use ($request) {
                return $query->where('angkatan', $request->angkatan);
            });

        return DataTables::of($mahasiswa)
            ->addIndexColumn()
            ->addColumn('prodi', function ($mhs) {
                return $mhs->prodi->nama_prodi ?? 'N/A';
            })
            ->addColumn('prestasi_count', function ($mhs) {
                return $mhs->prestasi->count();
            })
            ->addColumn('action', function ($mhs) {
                return '<a href="' . route('laporan-prestasi.mahasiswa.show', $mhs->id_mahasiswa) . '" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye"></i> Pilih
                </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show student achievement detail (A.a)
     */
    public function showMahasiswa($id)
    {
        $mahasiswa = MahasiswaModel::with([
            'prodi',
            'prestasi' => function ($query) {
                $query->with(['tingkatPrestasi', 'kategori', 'periode', 'dosenPembimbing'])
                    ->orderBy('tanggal_prestasi', 'desc');
            }
        ])->findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Detail Prestasi Mahasiswa',
            'list' => ['Laporan Prestasi', 'Mahasiswa', 'Detail']
        ];

        return view('laporanprestasi.detail', [
            'breadcrumb' => $breadcrumb,
            'mahasiswa' => $mahasiswa,
            'activeMenu' => 'laporan-prestasi',
            'isMahasiswa' => false
        ]);
    }

    public function listPeriode()
    {
        $breadcrumb = (object) [
            'title' => 'Laporan Berdasarkan Periode',
            'list' => ['Laporan Prestasi', 'Periode']
        ];

        $periode = PeriodeModel::all();

        return view('laporanprestasi.periode', [
            'breadcrumb' => $breadcrumb,
            'periode' => $periode,
            'activeMenu' => 'laporan-prestasi'
        ]);
    }

    /**
     * Get achievement data by period for datatable (A.b)
     */
    public function listByPeriode(Request $request)
    {
        $query = PrestasiMahasiswaModel::with([
            'anggota',
            'kategori',
            'tingkatPrestasi',
            'periode'
        ])->where('status_verifikasi', 1)
            ->when($request->id_periode, function ($query) use ($request) {
                return $query->where('id_periode', $request->id_periode)->where('status_verifikasi', 1);
            });

        $prestasi = $query->get();

        return DataTables::of($prestasi)
            ->addIndexColumn()
            ->addColumn('nama_mahasiswa', function ($p) {
                return $p->anggota ?? 'N/A';
            })
            ->addColumn('nim', function ($p) {
                return $p->anggota ?? 'N/A';
            })
            ->addColumn('kategori', function ($p) {
                return $p->kategori->nama_kategori ?? 'N/A';
            })
            ->addColumn('tingkat', function ($p) {
                return $p->tingkatPrestasi->nama_tingkat_prestasi ?? 'N/A';
            })
            ->addColumn('periode', function ($p) {
                return $p->periode ? $p->periode->semester . ' - ' . $p->periode->tahun_ajaran : 'N/A';
            })
            ->addColumn('bukti_sertifikat', function ($p) {
                if ($p->bukti_sertifikat) {
                    return '<a href="' . asset('storage/' . $p->bukti_sertifikat) . '" target="_blank" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> Lihat
                    </a>';
                }
                return 'Tidak ada';
            })
            ->rawColumns(['bukti_sertifikat'])
            ->make(true);
    }

    public function exportMahasiswa($id)
    {
        $mahasiswa = MahasiswaModel::with([
            'prodi',
            'prestasi' => function ($query) {
                $query->with(['tingkatPrestasi', 'kategori', 'periode', 'dosenPembimbing'])
                    ->orderBy('tanggal_prestasi', 'desc');
            }
        ])->findOrFail($id);

        $format = request('format', 'pdf');

        if ($format === 'excel') {
            return $this->exportMahasiswaExcel($mahasiswa);
        }

        return $this->exportMahasiswaPdf($mahasiswa);
    }

    private function exportMahasiswaPdf($mahasiswa)
    {
        $pdf = Pdf::loadView('laporanprestasi.exports.mahasiswa_pdf', [
            'mahasiswa' => $mahasiswa,
            'title' => 'Laporan Prestasi Mahasiswa - ' . $mahasiswa->nama
        ]);

        return $pdf->download('prestasi-mahasiswa-' . $mahasiswa->nim . '.pdf');
    }

    private function exportMahasiswaExcel($mahasiswa)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Laporan Prestasi Mahasiswa');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Student info
        $sheet->setCellValue('A3', 'NIM');
        $sheet->setCellValue('B3', $mahasiswa->nim);
        $sheet->setCellValue('A4', 'Nama');
        $sheet->setCellValue('B4', $mahasiswa->nama);
        $sheet->setCellValue('A5', 'Prodi');
        $sheet->setCellValue('B5', $mahasiswa->prodi->nama_prodi ?? 'N/A');

        // Table header
        $sheet->setCellValue('A7', 'No');
        $sheet->setCellValue('B7', 'Nama Prestasi');
        $sheet->setCellValue('C7', 'Juara');
        $sheet->setCellValue('D7', 'Kategori');
        $sheet->setCellValue('E7', 'Tingkat');
        $sheet->setCellValue('F7', 'Periode Lomba');
        $sheet->setCellValue('G7', 'Dosen Pembimbing');

        // Table data
        $row = 8;
        foreach ($mahasiswa->prestasi as $index => $prestasi) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $prestasi->nama_prestasi);
            $sheet->setCellValue('C' . $row, $prestasi->juara);
            $sheet->setCellValue('D' . $row, $prestasi->kategori->nama_kategori ?? 'N/A');
            $sheet->setCellValue('E' . $row, $prestasi->tingkatPrestasi->nama_tingkat_prestasi ?? 'N/A');
            $sheet->setCellValue('F' . $row, $prestasi->periode ? $prestasi->periode->semester . ' - ' . $prestasi->periode->tahun_ajaran : 'N/A');
            $sheet->setCellValue('G' . $row, $prestasi->dosenPembimbing->nama ?? 'N/A');
            $row++;
        }

        // Styling
        $sheet->getStyle('A7:G7')->getFont()->setBold(true);
        $sheet->getStyle('A7:G' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A7:G7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFDDDDDD');

        // Auto size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'prestasi-mahasiswa-' . $mahasiswa->nim . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function exportPeriode($id_periode)
    {
        $periode = PeriodeModel::findOrFail($id_periode);
        $prestasi = PrestasiMahasiswaModel::with([
            'anggota',
            'kategori',
            'tingkatPrestasi',
            'periode',
            'dosenPembimbing'
        ])
            ->where('id_periode', $id_periode)
            ->where('status_verifikasi', 1)
            ->orderBy('tanggal_prestasi', 'desc')
            ->get();

        $format = request('format', 'pdf');

        if ($format === 'excel') {
            return $this->exportPeriodeExcel($periode, $prestasi);
        }

        return $this->exportPeriodePdf($periode, $prestasi);
    }

    private function exportPeriodePdf($periode, $prestasi)
    {
        // Bersihkan karakter tidak valid dari nama file
        $semester = str_replace(['/', '\\'], '-', $periode->semester);
        $tahun_ajaran = str_replace(['/', '\\'], '-', $periode->tahun_ajaran);

        $pdf = Pdf::loadView('laporanprestasi.exports.periode_pdf', [
            'periode' => $periode,
            'prestasi' => $prestasi,
            'title' => 'Laporan Prestasi Periode ' . $periode->semester . ' - ' . $periode->tahun_ajaran
        ]);

        $filename = 'prestasi-periode-' . $semester . '-' . $tahun_ajaran . '.pdf';

        return $pdf->download($filename);
    }

    private function exportPeriodeExcel($periode, $prestasi)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Laporan Prestasi Periode ' . $periode->semester . ' - ' . $periode->tahun_ajaran);
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Table header
        $headers = ['No', 'Nama Mahasiswa', 'NIM', 'Kategori', 'Nama Prestasi', 'Tingkat', 'Juara', 'Periode Lomba', 'Dosen Pembimbing'];
        $sheet->fromArray($headers, null, 'A3');

        // Table data
        $row = 4;
        foreach ($prestasi as $index => $p) {
            // Ambil semua nama mahasiswa dan gabungkan dengan koma
            $namaMahasiswa = $p->anggota->pluck('nama')->implode(', ');
            // Ambil semua NIM dan gabungkan dengan koma
            $nimMahasiswa = $p->anggota->pluck('nim')->implode(', ');

            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $namaMahasiswa ?: 'N/A');
            $sheet->setCellValue('C' . $row, $nimMahasiswa ?: 'N/A');
            $sheet->setCellValue('D' . $row, $p->kategori->nama_kategori ?? 'N/A');
            $sheet->setCellValue('E' . $row, $p->nama_prestasi);
            $sheet->setCellValue('F' . $row, $p->tingkatPrestasi->nama_tingkat_prestasi ?? 'N/A');
            $sheet->setCellValue('G' . $row, $p->juara);
            $sheet->setCellValue('H' . $row, $p->periode ? $p->periode->semester . ' - ' . $p->periode->tahun_ajaran : 'N/A');
            $sheet->setCellValue('I' . $row, $p->dosenPembimbing->nama ?? 'N/A');
            $row++;
        }

        // Styling
        $sheet->getStyle('A3:I3')->getFont()->setBold(true);
        $sheet->getStyle('A3:I' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A3:I3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFDDDDDD');

        // Auto size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set wrap text untuk kolom nama dan NIM karena mungkin panjang
        $sheet->getStyle('B4:C' . ($row - 1))->getAlignment()->setWrapText(true);
        $writer = new Xlsx($spreadsheet);
        $filename = 'prestasi-periode-' . $periode->semester . '-' . $periode->tahun_ajaran . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function showByUser()
    {
        // Pastikan hanya mahasiswa yang bisa akses
        if (auth()->user()->role_id != 3) {
            abort(403, 'Akses hanya untuk mahasiswa');
        }

        $mahasiswa = MahasiswaModel::with([
            'prodi',
            'prestasi' => function ($query) {
                $query->with(['tingkatPrestasi', 'kategori', 'periode', 'dosenPembimbing'])
                    ->orderBy('tanggal_prestasi', 'desc');
            }
        ])
            ->where('id_pengguna', auth()->id())
            ->firstOrFail();

        return view('laporanprestasi.detail_mahasiswa', [
            'mahasiswa' => $mahasiswa,
            'activeMenu' => 'laporan_prestasi',
            'breadcrumb' => (object) [
                'title' => 'Laporan Prestasi',
                'list' => ['Laporan Prestasi']
            ]
        ]);
    }
}
