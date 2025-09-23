@extends('layouts.template', ['page' => $breadcrumb->title])
@section('content')
@include('layouts.navbar', ['title' => $breadcrumb->list])
<div class="container-fluid py-4 h-100 flex-grow-1">
    <h3 class="text-dark">Selamat Datang {{ getuserName() }}!</h3>
    <div class="col flex-grow-1">
        <div class="flex-grow-1">
            @if (auth()->user()->role_id == 3)
            <div class="card mx-auto border" style="width: 100%">
                <div class="card-body p-4">
                    <h5 class="font-weight-bolder mb-4">Rekomendasi lomba untuk anda</h5>
                    <div class="row">
                        @foreach ($rekomLomba['data'] as $lomba)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card shadow w-100 d-flex flex-column position-relative h-100">
                                <!-- Foto Lomba -->
                                <div class="d-flex justify-content-center align-items-center"
                                    style="height: 200px; width: 150px; margin: auto; border-radius: 25px; overflow: hidden;">
                                    @if ($lomba['foto'])
                                    <a href="{{ asset('storage/' . $lomba['foto']) }}" target="_blank"
                                        rel="noopener noreferrer">
                                        <img src="{{ asset('storage/' . $lomba['foto']) }}" alt="Poster Lomba"
                                            class="w-100 h-100 shadow-lg"
                                            style="object-fit: cover; border-radius: 25px;">
                                    </a>
                                    @else
                                    <div
                                        class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-image fa-3x text-secondary"></i>
                                    </div>
                                    @endif
                                </div>

                                <!-- Informasi Lomba -->
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $lomba['nama_lomba'] }}</h5>

                                    <!-- Kategori -->
                                    <div class="mb-2 d-flex flex-wrap gap-1">
                                        @foreach ($lomba['kategoris'] as $kategori)
                                        <span
                                            class="badge bg-warning text-wrap">{{ $kategori['nama_kategori'] }}</span>
                                        @endforeach
                                    </div>

                                    <!-- Deskripsi -->
                                    <p class="card-text text-sm mb-2">
                                        {{ Str::limit($lomba['deskripsi'], 100) }}
                                    </p>

                                    <!-- Link Pendaftaran -->
                                    @if ($lomba['link_pendaftaran'])
                                    <p class="card-text text-sm mb-2">
                                        <a href="{{ $lomba['link_pendaftaran'] }}"
                                            target="_blank">{{ $lomba['link_pendaftaran'] }}</a>
                                    </p>
                                    @endif

                                    <!-- Skor Moora dan Tombol -->
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <div>
                                            {!! $lomba['aksi'] !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="col mx-auto d-flex flex-column gap-3 mt-4 w-100" style="max-width: 1300px">
        <!-- Row 1: Statistik dan Doughnut Chart -->
        <div class="row text-center">
            <div class="col-md-3 flex-grow-1 d-flex flex-column gap-3">
                <!-- Card Jumlah Prestasi -->
                <div class="flex-grow-1">
                    <div class="card bg-gradient-warning text-white mx-auto border"
                        style="width: 100%; max-width: 4000px; height:100%;">
                        <div class="card-body p-4">
                            <div class="d-flex gap-2 w-100 h-100">
                                <div class="d-flex flex-column gap-1 col-9">
                                    <p class="text-md mb-0 fs-6 text-uppercase font-weight-bold text-start">Jumlah
                                        Prestasi Mahasiwa</p>
                                    <h5 class="font-weight-bolder fs-2 text-start">{{ $jmlPrestasi }}</h5>
                                </div>
                                <div class="text-end col-3">
                                    <div
                                        class="icon icon-shape bg-light border shadow-primary text-center rounded-circle">
                                        <i class="ni ni-trophy text-warning text-lg opacity-10"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Lomba Aktif -->
                <div class="flex-grow-1">
                    <div class="card bg-gradient-warning text-white mx-auto border"
                        style="width: 100%; max-width: 4000px; height:100%;">
                        <div class="card-body p-4">
                            <div class="d-flex gap-2 w-100 h-100">
                                <div class="d-flex flex-column gap-1 col-9">
                                    <p class="text-md mb-0 fs-6 text-uppercase font-weight-bold text-start">
                                        {{ auth()->user()->role_id == 1 ? 'Lomba yang sedang berlangsung' : 'Lomba yang aktif' }}
                                    </p>
                                    <h5 class="font-weight-bolder fs-2 text-start">{{ $jmlLomba }}</h5>
                                </div>
                                <div class="text-end col-3">
                                    <div
                                        class="icon icon-shape bg-light border shadow-primary text-center rounded-circle">
                                        <i class="ni ni-trophy text-warning text-lg opacity-10"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prestasi mahasiswa per kategori-->
            <div class="col-md-8 flex-grow-1">
                <div class="flex-grow-1">
                    <div class="card mx-auto border" style="width: 100%; height:400px">
                        <div class="card-body p-4">
                            <h5 class="font-weight-bolder mb-4">Distribusi Prestasi Mahasiswa Per Kategori</h5>
                            <div class="chart w-100">
                                <canvas id="doughnut-chart" class="mx-auto chart-canvas w-100" style="max-width: 600px"
                                    height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Distribusi Prestasi Mahasiswa Berdasarkan Tingkat dan Top Mahasiswa Berprestasi -->
        <div class="row">
            <!-- Distribusi Prestasi Mahasiswa Berdasarkan Tingkat -->
            <div class="col-md-8 flex-grow-1">
                <div class="card mx-auto border" style="width: 100%; height:500px">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bolder mb-4">Distribusi Prestasi Mahasiswa Berdasarkan Tingkat</h5>
                        <div class="chart w-100">
                            <canvas id="bar-chart" class="mx-auto chart-canvas w-100" style="max-width: 700px"
                                height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leaderboard -->
            <div class="col-md-4 flex-grow-1">
                <div class="card mx-auto border" style="width: 100%; height:500px">
                    <div class="card-body p-4 d-flex flex-column gap-2">
                        <h5 class="font-weight-bolder mb-2">Top Mahasiswa Berprestasi</h5>
                        <div class="card card-carousel overflow-hidden h-100 p-0">
                            <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
                                <div class="carousel-inner border-radius-lg h-100">
                                    @foreach ($rankMahasiswaByPrestasi as $index => $mahasiswa)
                                    <div class="carousel-item h-100 {{ $index === 0 ? 'active' : '' }}"
                                        style="background-image: url('{{ asset($mahasiswa['foto'] ? 'storage/' . $mahasiswa['foto'] : 'image/fotoDefault.jpg') }}');
                                background-size: cover; background-position: center; position: relative;">

                                        <!-- Gradient Overlay -->
                                        <div
                                            style="position: absolute; bottom: 0; left: 0; right: 0; height: 40%; 
                                    background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);">
                                        </div>

                                        <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-3"
                                            style="z-index: 1;">
                                            <div
                                                class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-1 pb-0">
                                                {{ $index + 1 }}
                                            </div>
                                            <h5 class="mb-1 text-white">{{ $mahasiswa['nama'] }}</h5>
                                            <p class="text-white">{{ $mahasiswa['jumlah'] }} Prestasi</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @if (count($rankMahasiswaByPrestasi) > 1)
                                <button class="carousel-control-prev w-5 me-3" type="button"
                                    data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next w-5 me-3" type="button"
                                    data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Perkembangan Prestasi Mahasiswa Per Semester dan Distribusi Lomba Per Kategori -->
        <div class="row">
            <!-- Perkembangan Prestasi Mahasiswa Per Semester -->
            <div class="col-md-6 flex-grow-1">
                <div class="card mx-auto border" style="width: 100%; height:400px">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bolder mb-4">Perkembangan Prestasi Mahasiswa Per Semester</h5>
                        <div class="chart w-100">
                            <canvas id="line-chart" class="my-auto chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Distribusi Lomba Per Kategori -->
            <div class="col-md-6 flex-grow-1">
                <div class="card mx-auto border" style="width: 100%; height:400px">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bolder mb-4">Distribusi Lomba Per Kategori</h5>
                        <div class="chart w-100">
                            <canvas id="pie-chart" class="my-auto chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@include('layouts.footer')
@endsection

@push('css')
<style>
    .carousel-item {
        transition: transform 0.6s ease-in-out;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 5%;
    }

    .carousel-caption {
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }
</style>
@endpush

@push('js')
<script src="{{ asset('argon/assets/js/plugins/chartjs.min.js') }}"></script>
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Doughnut Chart - Prestasi by Kategori
        const prestasiData = @json($jumlahPrestasiByKategori);
        const doughnutLabels = prestasiData.map(item => item.nama_kategori);
        const doughnutData = prestasiData.map(item => item.jumlah);

        const doughnutColors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
            '#FF9F40', '#8AC24A', '#607D8B', '#E91E63', '#00BCD4'
        ];

        const doughnutCtx = document.getElementById('doughnut-chart').getContext('2d');
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: doughnutLabels,
                datasets: [{
                    data: doughnutData,
                    backgroundColor: doughnutColors,
                    hoverBackgroundColor: doughnutColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'left',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Bar Chart - Tingkat Prestasi
        const tingkatPrestasiData = @json($tingkatPrestasiDicapai);
        const orderedLevels = ['Internasional', 'Nasional', 'Provinsi', 'Kota', 'Kampus'];

        // Urutkan data sesuai dengan orderedLevels
        const sortedData = orderedLevels.map(level => {
            const found = tingkatPrestasiData.find(item => item.nama_tingkat_prestasi === level);
            return found || {
                nama_tingkat_prestasi: level,
                jumlah: 0
            };
        });

        const barLabels = sortedData.map(item => item.nama_tingkat_prestasi);
        const barData = sortedData.map(item => item.jumlah);

        const barCtx = document.getElementById('bar-chart').getContext('2d');
        const gradient = barCtx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(54, 162, 235, 0.8)');
        gradient.addColorStop(1, 'rgba(54, 162, 235, 0.2)');

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: barLabels,
                datasets: [{
                    label: 'Jumlah Prestasi',
                    data: barData,
                    backgroundColor: gradient,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                    hoverBackgroundColor: 'rgba(54, 162, 235, 0.6)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Prestasi',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            drawOnChartArea: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tingkat Prestasi',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Jumlah: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });

        // Pie Chart - Lomba by Kategori
        const lombaData = @json($lombaByKategori);
        const pieLabels = lombaData.map(item => item.kategori);
        const pieData = lombaData.map(item => item.jumlah);

        const pieColors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
            '#FF9F40', '#8AC24A', '#607D8B', '#E91E63', '#00BCD4'
        ];

        const pieCtx = document.getElementById('pie-chart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                    backgroundColor: pieColors,
                    hoverBackgroundColor: pieColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Line Chart - Prestasi per Semester
        const prestasiPeriodeData = @json($prestasiMahasiswaPerSemester);
        const lineLabels = prestasiPeriodeData.map(item => item.periode);
        const lineData = prestasiPeriodeData.map(item => item.jumlah);

        const lineCtx = document.getElementById('line-chart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: lineLabels,
                datasets: [{
                    label: 'Jumlah Prestasi',
                    data: lineData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Prestasi',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            drawOnChartArea: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Periode Akademik',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Jumlah: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush