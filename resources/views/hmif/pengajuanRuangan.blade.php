@extends('layouts.starter')

@section('header-content')
    <h3>Pengajuan Ruangan</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <!-- Validation error messages -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form for searching available rooms -->
        <form action="{{ route('ketersediaanRuangan') }}" method="POST" class="mb-3">
            @csrf

            <!-- Input tanggal dan waktu -->
            <div class="mb-3">
                <label for="tanggal_peminjaman" class="form-label">Tanggal Peminjaman</label>
                <input type="date" name="tanggal_peminjaman" id="tanggal_peminjaman" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="waktu_peminjaman" class="form-label">Waktu Peminjaman</label>
                <input type="time" name="waktu_peminjaman" id="waktu_peminjaman" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="tanggal_batas" class="form-label">Batas Tanggal</label>
                <input type="date" name="tanggal_batas" id="tanggal_batas" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="waktu_batas" class="form-label">Batas Waktu</label>
                <input type="time" name="waktu_batas" id="waktu_batas" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Cari Ruangan Tersedia</button>
        </form>


@endsection
