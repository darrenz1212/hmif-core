@extends('layouts.starter')

@section('header-content')
    <h3>Halo Anggota HMIF</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <h1>Halo Anggota HMIF, {{ Auth::user()->name }}</h1>

        <!-- Buttons -->
        <a href="{{ url('hmif/ketersediaanRuangan') }}" class="btn btn-primary">Cek Ketersediaan Ruangan</a>
        <a href="{{ url('hmif/pengajuanRuangan') }}" class="btn btn-primary mr-2">Ajukan Peminjaman Ruangan</a>
        <a href="{{ url('hmif/statusPemRuangan') }}" class="btn btn-primary mr-2">Cek Status Peminjaman Ruangan</a>
        <a href="{{ url('inventaris/index') }}" class="btn btn-primary mr-2">Ajukan Peminjaman Inventaris</a>
    </div>
@endsection
