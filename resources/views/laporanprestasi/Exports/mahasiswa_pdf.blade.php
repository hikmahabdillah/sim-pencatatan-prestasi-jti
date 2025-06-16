<!DOCTYPE html>
<html>

    <head>
        <title>{{ $title }}</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                font-size: 10pt;
                line-height: 1.4;
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
                margin-bottom: 5px;
            }

            .info-table {
                width: 100%;
                margin-bottom: 15px;
                border-collapse: collapse;
            }

            .info-table td {
                border: 1px solid #aaa;
                padding: 6px;
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
                word-wrap: break-word;
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

            .footer {
                margin-top: 40px;
                font-size: 9pt;
                text-align: right;
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
        <p style="text-align: center; font-size: 9pt;">Dicetak pada: {{ date('d/m/Y H:i') }}</p>

        <!-- Informasi Mahasiswa -->
        <table class="info-table">
            <tr>
                <td><strong>NIM</strong></td>
                <td>{{ $mahasiswa->nim }}</td>
                <td><strong>Program Studi</strong></td>
                <td>{{ $mahasiswa->prodi->nama_prodi ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Nama Mahasiswa</strong></td>
                <td>{{ $mahasiswa->nama }}</td>
                <td><strong>Angkatan</strong></td>
                <td>{{ $mahasiswa->angkatan }}</td>
            </tr>
        </table>

        <!-- Daftar Prestasi -->
        <h3>Daftar Prestasi</h3>

        @if ($mahasiswa->prestasi->isEmpty())
            <p style="font-size: 10pt;">Tidak ada data prestasi</p>
        @else
            <table class="prestasi-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Nama Prestasi</th>
                        <th width="10%">Juara</th>
                        <th width="15%">Kategori</th>
                        <th width="10%">Tingkat</th>
                        <th width="15%">Periode</th>
                        <th width="25%">Dosen Pembimbing</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mahasiswa->prestasi as $index => $prestasi)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $prestasi->nama_prestasi }}</td>
                            <td>{{ $prestasi->juara }}</td>
                            <td>{{ $prestasi->kategori->nama_kategori ?? 'N/A' }}</td>
                            <td>{{ $prestasi->tingkatPrestasi->nama_tingkat_prestasi ?? 'N/A' }}</td>
                            <td>
                                @if ($prestasi->periode)
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

        <!-- Footer -->
        <div class="footer">
            <p>Sistem Informasi Prestasi Mahasiswa</p>
        </div>
    </body>

</html>
