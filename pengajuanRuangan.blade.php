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

        <form action="{{ route('submitPengajuanRuangan') }}" method="POST" enctype="multipart/form-data" class="p-4 border rounded shadow-sm">
            @csrf

            <!-- Form fields -->
            <div class="mb-3">
                <label for="id_ruangan" class="form-label">Nama Ruangan</label>
                <select class="form-control" name="id_ruangan" id="id_ruangan" required>
                    <option value="">Pilih Ruangan</option>
                    @foreach($ruanganList as $ruangan)
                        <option value="{{ $ruangan->room_id }}" {{ old('id_ruangan') == $ruangan->room_id ? 'selected' : '' }}>
                            {{ $ruangan->nama_ruangan }}
                        </option>
                    @endforeach
                </select>
            </div>




            <div class="mb-3">
                <label for="id_peminjam" class="form-label">Nama Peminjam</label>
                <select class="form-control" name="id_peminjam" id="id_peminjam" required>
                    <option value="">Pilih Peminjam</option>
                    @foreach($userList as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="surat_peminjaman" class="form-label">Surat Peminjaman (PDF only)</label>
                <input type="file" class="form-control" name="surat_peminjaman" id="surat_peminjaman" accept="application/pdf" required>
            </div>

            <!-- Other form fields -->
            <div class="mb-3">
                <label for="keterangan_peminjaman" class="form-label">Keterangan Peminjaman</label>
                <input type="text" class="form-control" name="keterangan_peminjaman" id="keterangan_peminjaman" maxlength="255">
            </div>
            <div class="mb-3">
                <label for="tanggal_peminjaman" class="form-label">Tanggal Peminjaman</label>
                <input type="date" class="form-control" name="tanggal_peminjaman" id="tanggal_peminjaman" required>
            </div>
            <div class="mb-3">
                <label for="waktu_peminjaman" class="form-label">Waktu Peminjaman</label>
                <input type="time" class="form-control" name="waktu_peminjaman" id="waktu_peminjaman" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>

        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
    </div>
@endsection
