@extends('layouts.template', ['page' => $breadcrumb->title])

@section('content')
@include('layouts.navbar', ['title' => $breadcrumb->list])
@php
    \Carbon\Carbon::setLocale('id');
@endphp
<div class="container-fluid py-4 h-100 flex-grow-1">

    <div class="card card-plain shadow-lg rounded-3 p-3 mb-4" style="height: 200px;">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="blur-shadow-image position-relative overflow-hidden"
                style="width: 150px; height: 200px; border-radius: 25px;">
                @php
                $foto = $data->poster ? asset('storage/' . $data->poster) : asset('image/fotoDefault.jpg');
                @endphp
                <img class="w-100 h-100 shadow-lg" style="object-fit: cover;" src="{{ $foto }}" alt="Poster Lomba">
            </div>
        </div>
    </div>
    <div class="card shadow rounded-3 border">
        <div class="card-body position-relative">
            <h5 class="fw-semibold mb-4 text-primary">Informasi Detail Lomba</h5>

            <div class="mb-2 d-flex align-items-center gap-4">
            <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Nama Lomba</p>
                <p class="mb-0 fw-semibold">:  {{ $data->nama_lomba }}</p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
            <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Tingkat Lomba</p>
                <p class="mb-0 fw-semibold">:  {{ $data->tingkatPrestasi->nama_tingkat_prestasi ?? '-' }}</p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
            <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Kategori Lomba</p>
                <p class="mb-0 fw-semibold">:  {{ $data->kategori->nama_kategori ?? '-' }}</p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
            <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Penyelenggara</p>
                <p class="mb-0 fw-semibold">:  {{ $data->penyelenggara ?? '-' }}</p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
            <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Deskripsi</p>
                <p class="mb-0 fw-semibold">:  {{ $data->deskripsi }}</p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
            <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Biaya Pendaftaran</p>
                <p class="mb-0 fw-semibold">:  
                    @if ($data->biaya_pendaftaran == 1)
                    Berbayar
                    @elseif ($data->biaya_pendaftaran == 0)
                    Tidak Berbayar
                    @else
                    -
                    @endif
                </p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
            <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Tanggal Mulai Lomba</p>
                <p class="mb-0 fw-semibold">:  {{ \Carbon\Carbon::parse($data->tanggal_mulai)->translatedFormat('d F Y') }}</p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
            <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Tanggal Selesai Lomba</p>
                <p class="mb-0 fw-semibold">:  {{ \Carbon\Carbon::parse($data->tanggal_selesai)->translatedFormat('d F Y') }}</p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
            <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Deadline Pendaftaran</p>
                <p class="mb-0 fw-semibold">:  {{ \Carbon\Carbon::parse($data->deadline_pendaftaran)->translatedFormat('d F Y') }}</p>
            </div>

            <div class="mb-2 d-flex align-items-center gap-4">
            <p class="mb-0 text-muted small fw-bold" style="min-width: 150px;">Link Pendaftaran</p>
                <p class="mb-0 fw-semibold">:  
                    <a href="{{ $data->link_pendaftaran}}" target="_blank">{{ $data->link_pendaftaran }}</a>
                </p>
            </div>
        </div>
       @include('layouts.footer')
        @endsection