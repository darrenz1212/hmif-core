@extends('layouts.starter')

@section('header-content')
@endsection

@section('main-content')
    <div class="container mt-4">
        <h1>Halo Anggota HMIF, {{ Auth::user()->name }}</h1>

        <a href="{{ url('hmif/pengajuanRuangan') }}" class="btn btn-primary mr-2">Ajukan Peminjaman Ruangan</a>
        <a href="{{ url('hmif/statusPemRuangan') }}" class="btn btn-primary mr-2">Cek Status Peminjaman Ruangan</a>
        <a href="{{ url('hmif/jadwalRuangan') }}" class="btn btn-primary mr-2">Lihat Jadwal Ruangan</a>
    </div>
@endsection