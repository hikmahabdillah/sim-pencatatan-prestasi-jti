@extends('layouts.template')

@section('title', 'Detail Perhitungan MOORA')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Detail Perhitungan MOORA</h2>
    <h5>Mahasiswa: {{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})</h5>
    <h5>Minat & Bakat:</h5>
    <ul>
        @foreach ($kategoriMinat as $kat)
        <li>{{ $kat->nama_kategori }}</li>
        @endforeach
    </ul>


    {{-- Step 1: Matriks Keputusan --}}
    <div class="card my-4">
        <div class="card-header bg-secondary text-white">
            Step 1: Matriks Keputusan (X)
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Alternatif</th>
                        <th>C1 (Minat) (B)</th>
                        <th>C2 (Prestasi) (B)</th>
                        <th>C3 (Tingkat) (B)</th>
                        <th>C4 (Durasi) (B)</th>
                        <th>C5 (Biaya)(C)</th>
                        <th>C6 (Benefit)(B)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hasil as $row)
                    <tr>
                        <td>{{ $row['id_lomba'] }}</td>
                        <td>{{ $row['c1'] }}</td>
                        <td>{{ $row['c2'] }}</td>
                        <td>{{ $row['c3'] }}</td>
                        <td>{{ $row['c4'] }}</td>
                        <td>{{ $row['c5'] }}</td>
                        <td>{{ $row['c6'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Step 2: Matriks Normalisasi --}}
    <div class="card my-4">
        <div class="card-header bg-info text-white">
            Step 2: Matriks Normalisasi (R)
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Alternatif</th>
                        <th>C1 (Minat) (B)</th>
                        <th>C2 (Prestasi) (B)</th>
                        <th>C3 (Tingkat) (B)</th>
                        <th>C4 (Durasi) (B)</th>
                        <th>C5 (Biaya)(C)</th>
                        <th>C6 (Benefit)(B)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hasil as $row)
                    <tr>
                        <td>{{ $row['id_lomba'] }}</td>
                        <td>{{ number_format($row['n_c1'], 4) }}</td>
                        <td>{{ number_format($row['n_c2'], 4) }}</td>
                        <td>{{ number_format($row['n_c3'], 4) }}</td>
                        <td>{{ number_format($row['n_c4'], 4) }}</td>
                        <td>{{ number_format($row['n_c5'], 4) }}</td>
                        <td>{{ number_format($row['n_c6'], 4) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Step 2.5: mengoptimasi nilai atribut (hasil normalisasi *bobot) --}}
    <div class="card my-4">
        <div class="card-header bg-info text-white">
            Step 2.5: mengoptimasi nilai atribut (hasil normalisasi *bobot)
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Alternatif</th>
                        <th>C1 (Minat) (B)</th>
                        <th>C2 (Prestasi) (B)</th>
                        <th>C3 (Tingkat) (B)</th>
                        <th>C4 (Durasi) (B)</th>
                        <th>C5 (Biaya)(C)</th>
                        <th>C6 (Benefit)(B)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hasil as $row)
                    <tr>
                        <td>{{ $row['id_lomba'] }}</td>
                        <td>{{ number_format($row['n_c1'] * 0.25, 4) }}</td> {{-- Minat --}}
                        <td>{{ number_format($row['n_c2'] * 0.20, 4) }}</td> {{-- Prestasi --}}
                        <td>{{ number_format($row['n_c3'] * 0.20, 4) }}</td> {{-- Tingkat --}}
                        <td>{{ number_format($row['n_c4'] * 0.15, 4) }}</td> {{-- Durasi (Cost) --}}
                        <td>{{ number_format($row['n_c5'] * 0.10, 4) }}</td> {{-- Biaya (Cost) --}}
                        <td>{{ number_format($row['n_c6'] * 0.10, 4) }}</td> {{-- Benefit tambahan --}}
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{-- Step 3: Perhitungan Skor MOORA --}}
    <div class="card my-4">
        <div class="card-header bg-warning text-white">
            Step 3: Perhitungan Skor MOORA
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>ID Lomba</th>
                        <th> max(C1+C2+C3+C4+C6)</th>
                        <th>min (C5)</th>
                        <th>Skor MOORA (yi)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hasil as $row)
                    <tr>
                        <td>{{ $row['id_lomba'] }}</td>
                        <td>{{ number_format($row['benefit'], 4) }}</td>
                        <td>{{ number_format($row['cost'], 4) }}</td>
                        <td><strong>{{ number_format($row['skor_moora'], 4) }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Step 4: Ranking --}}
    <div class="card my-4">
        <div class="card-header bg-success text-white">
            Step 4: Peringkat Rekomendasi Lomba
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Ranking</th>
                        <th>ID Lomba</th>
                        <th>Nama Lomba</th>
                        <th>Skor MOORA (yi)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $sorted = collect($hasil)->sortByDesc('skor_moora')->values();
                    @endphp
                    @foreach ($sorted as $i => $row)
                    <tr>
                        <td><strong>{{ $i + 1 }}</strong></td>
                        <td>{{ $row['id_lomba'] }}</td>
                        <td>
                            <strong>{{ $lombaMap[$row['id_lomba']] ?? 'Lomba tidak ditemukan' }}</strong>
                        </td>
                        <td>{{ number_format($row['skor_moora'], 4) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection