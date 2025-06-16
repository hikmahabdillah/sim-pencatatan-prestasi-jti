<!DOCTYPE html>
<html>

    <head>
        <title>{{ $title }}</title>
        <style>
            @page {
                size: A4 landscape;
                margin: 20mm;
            }

            body {
                font-family: Arial, sans-serif;
                font-size: 10pt;
                line-height: 1.4;
                margin: 0;
            }

            .header-table {
                width: 100%;
                border-bottom: 2px solid #000;
                margin-bottom: 20px;
            }

            .header-table td {
                vertical-align: top;
            }

            .logo {
                height: 80px;
            }

            .institution-info {
                text-align: center;
                font-size: 10pt;
            }

            .institution-info span {
                display: block;
            }

            h1 {
                text-align: center;
                font-size: 14pt;
                margin: 10px 0;
            }

            p {
                text-align: center;
                font-size: 9pt;
                margin-bottom: 20px;
            }

            .prestasi-table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }

            .prestasi-table th,
            .prestasi-table td {
                border: 1px solid #444;
                padding: 6px;
                word-break: break-word;
                font-size: 9pt;
            }

            .prestasi-table th {
                background-color: #f0f0f0;
                text-align: center;
            }

            .text-center {
                text-align: center;
            }
        </style>
    </head>

    <body>
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td width="15%" class="text-center">
                    <img src="{{ public_path('image/polinema-bw.png') }}" class="logo" alt="Polinema Logo">
                </td>
                <td width="85%" class="institution-info">
                    <span><strong>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</strong></span>
                    <span><strong>POLITEKNIK NEGERI MALANG</strong></span>
                    <span>Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                    <span>Telepon (0341) 404424 Pes. 101-105, Fax. (0341) 404420</span>
                    <span>Laman: www.polinema.ac.id</span>
                </td>
            </tr>
        </table>

        <!-- Judul -->
        <h1>{{ $title }}</h1>
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>

        <!-- Tabel Prestasi -->
        <table class="prestasi-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="18%">Nama Mahasiswa</th>
                    <th width="12%">NIM</th>
                    <th width="12%">Kategori</th>
                    <th width="18%">Nama Prestasi</th>
                    <th width="10%">Tingkat</th>
                    <th width="10%">Juara</th>
                    <th width="10%">Periode</th>
                    <th width="15%">Dosen Pembimbing</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prestasi as $index => $p)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            @if ($p->anggota->isNotEmpty())
                                {{ $p->anggota->pluck('nama')->implode(', ') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if ($p->anggota->isNotEmpty())
                                {{ $p->anggota->pluck('nim')->implode(', ') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $p->kategori->nama_kategori ?? 'N/A' }}</td>
                        <td>{{ $p->nama_prestasi }}</td>
                        <td>{{ $p->tingkatPrestasi->nama_tingkat_prestasi ?? 'N/A' }}</td>
                        <td class="text-center">{{ $p->juara ?? 'N/A' }}</td>
                        <td class="text-center">
                            {{ $p->periode ? $p->periode->semester . ' - ' . $p->periode->tahun_ajaran : 'N/A' }}
                        </td>
                        <td>{{ $p->dosenPembimbing->nama ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>

</html>
