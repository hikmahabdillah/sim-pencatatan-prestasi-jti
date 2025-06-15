<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .prestasi-table { width: 100%; border-collapse: collapse; }
        .prestasi-table th, .prestasi-table td { padding: 8px; border: 1px solid #ddd; }
        .prestasi-table th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table class="prestasi-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Mahasiswa</th>
                <th width="15%">NIM</th>
                <th width="15%">Kategori</th>
                <th width="20%">Nama Prestasi</th>
                <th width="10%">Tingkat</th>
                <th width="10%">Periode</th>
                <th width="15%">Dosen Pembimbing</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prestasi as $index => $p)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $p->anggota->first()->nama ?? 'N/A' }}</td>
                <td>{{ $p->anggota->first()->nim ?? 'N/A' }}</td>
                <td>{{ $p->kategori->nama_kategori ?? 'N/A' }}</td>
                <td>{{ $p->nama_prestasi }}</td>
                <td>{{ $p->tingkatPrestasi->nama_tingkat_prestasi ?? 'N/A' }}</td>
                <td>{{ $p->periode ? $p->periode->semester.' - '.$p->periode->tahun_ajaran : 'N/A' }}</td>
                <td>{{ $p->dosenPembimbing->nama ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>