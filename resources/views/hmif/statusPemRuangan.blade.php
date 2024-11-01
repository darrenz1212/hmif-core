@extends('layouts.starter')

@section('header-content')
    <h3>Status Peminjaman Ruangan</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <h4>Daftar Pengajuan Ruangan</h4>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>ID Peminjaman</th>
                    <th>Nama Ruangan</th>
                    <th>Nama Peminjam</th>
                    <th>Surat Peminjaman</th>
                    <th>Keterangan</th>
                    <th>Tanggal Peminjaman</th>
                    <th>Waktu Peminjaman</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjamanRuangan as $peminjaman)
                    <tr>
                        <td>{{ $peminjaman->id_peminjaman_ruangan }}</td> <!-- Use id_peminjaman_ruangan instead of id -->
                        <td>{{ $peminjaman->id_ruangan }}</td> <!-- Assuming room name is stored as an ID -->
                        <td>{{ $peminjaman->id_peminjam }}</td> <!-- Assuming borrower name is stored as an ID -->
                        <td><a href="{{ Storage::url($peminjaman->surat_peminjaman) }}" target="_blank">View PDF</a></td>
                        <td>{{ $peminjaman->keterangan_peminjaman }}</td>
                        <td>{{ $peminjaman->tanggal_peminjaman }}</td>
                        <td>{{ $peminjaman->waktu_peminjaman }}</td>
                        <td>{{ $peminjaman->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
