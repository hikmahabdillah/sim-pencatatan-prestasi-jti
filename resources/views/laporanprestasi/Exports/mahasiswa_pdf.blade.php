<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 5px; border: 1px solid #ddd; }
        .info-table tr:nth-child(even) { background-color: #f9f9f9; }
        .prestasi-table { width: 100%; border-collapse: collapse; }
        .prestasi-table th, .prestasi-table td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        .prestasi-table th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; font-size: 12px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="20%"><strong>NIM</strong></td>
            <td width="30%">{{ $mahasiswa->nim }}</td>
            <td width="20%"><strong>Program Studi</strong></td>
            <td>{{ $mahasiswa->prodi->nama_prodi ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Mahasiswa</strong></td>
            <td>{{ $mahasiswa->nama }}</td>
            <td><strong>Angkatan</strong></td>
            <td>{{ $mahasiswa->angkatan }}</td>
        </tr>
    </table>

    <h3>Daftar Prestasi</h3>
    @if($mahasiswa->prestasi->isEmpty())
    <p>Tidak ada data prestasi</p>
    @else
    <table class="prestasi-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="25%">Nama Prestasi</th>
                <th width="10%">Juara</th>
                <th width="15%">Kategori</th>
                <th width="10%">Tingkat</th>
                <th width="15%">Periode Lomba</th>
                <th width="20%">Dosen Pembimbing</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswa->prestasi as $index => $prestasi)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $prestasi->nama_prestasi }}</td>
                <td>{{ $prestasi->juara }}</td>
                <td>{{ $prestasi->kategori->nama_kategori ?? 'N/A' }}</td>
                <td>{{ $prestasi->tingkatPrestasi->nama_tingkat_prestasi ?? 'N/A' }}</td>
                <td>
                    @if($prestasi->periode)
                        {{ $prestasi->periode->semester }} - {{ $prestasi->periode->tahun_ajaran }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $prestasi->dosenPembimbing->nama ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Sistem Informasi Prestasi Mahasiswa</p>
    </div>
</body>
</html>