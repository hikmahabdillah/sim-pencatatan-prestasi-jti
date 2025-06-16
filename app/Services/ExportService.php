<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\MahasiswaModel;

class ExportService
{
    public function exportExcelMahasiswa(MahasiswaModel $mahasiswa)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul
        $sheet->setCellValue('A1', 'Laporan Prestasi Mahasiswa');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Info mahasiswa
        $sheet->setCellValue('A3', 'NIM');
        $sheet->setCellValue('B3', $mahasiswa->nim);
        $sheet->setCellValue('A4', 'Nama');
        $sheet->setCellValue('B4', $mahasiswa->nama);
        $sheet->setCellValue('A5', 'Prodi');
        $sheet->setCellValue('B5', $mahasiswa->prodi->nama_prodi ?? 'N/A');

        // Header tabel
        $sheet->setCellValue('A7', 'No');
        $sheet->setCellValue('B7', 'Nama Prestasi');
        $sheet->setCellValue('C7', 'Juara');
        $sheet->setCellValue('D7', 'Kategori');
        $sheet->setCellValue('E7', 'Tingkat');
        $sheet->setCellValue('F7', 'Periode Lomba');
        $sheet->setCellValue('G7', 'Dosen Pembimbing');
        $sheet->setCellValue('H7', 'Tanggal');

        // Style header tabel
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DDDDDD']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];
        $sheet->getStyle('A7:H7')->applyFromArray($headerStyle);

        // Isi data
        $row = 8;
        foreach ($mahasiswa->prestasi as $index => $prestasi) {
            $sheet->setCellValue('A'.$row, $index + 1);
            $sheet->setCellValue('B'.$row, $prestasi->nama_prestasi);
            $sheet->setCellValue('C'.$row, $prestasi->juara);
            $sheet->setCellValue('D'.$row, $prestasi->kategori->nama_kategori ?? 'N/A');
            $sheet->setCellValue('E'.$row, $prestasi->tingkatPrestasi->nama_tingkat_prestasi ?? 'N/A');
            $sheet->setCellValue('F'.$row, 
                $prestasi->periode ? $prestasi->periode->semester.' - '.$prestasi->periode->tahun_ajaran : 'N/A');
            $sheet->setCellValue('G'.$row, $prestasi->dosenPembimbing->nama ?? 'N/A');
            $sheet->setCellValue('H'.$row, $prestasi->tanggal_prestasi->format('d/m/Y'));

            // Style isi tabel
            $sheet->getStyle('A'.$row.':H'.$row)->getBorders()
                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $row++;
        }

        // Auto size kolom
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Buat writer dan simpan ke temporary file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'prestasi-mahasiswa-'.$mahasiswa->nim.'.xlsx';
        $tempPath = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempPath);

        return $tempPath;
    }
}