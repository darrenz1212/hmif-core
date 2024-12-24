@extends('layouts.starter')

@section('header-content')
    <h3>Status Peminjaman Ruangan</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <h4>Daftar Pengajuan Ruangan</h4>
        <table class="table table-bordered table-striped mt-3">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

            <thead>
                <tr>
                    <th>Nama Ruangan</th>
                    <th>Nama Peminjam</th>
                    <th>Surat Peminjaman</th>
                    <th>Keterangan</th>
                    <th>Tanggal Peminjaman</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjamanRuangan as $peminjaman)
                    <tr>
                        <td>{{ $peminjaman->nama_ruangan }}</td>
                        <td>{{ $peminjaman->nama_peminjam }}</td>
                        <td>
                            @if($peminjaman->surat_peminjaman)
                                <a href="{{ Storage::url($peminjaman->surat_peminjaman) }}" target="_blank">Lihat Surat</a>
                            @else
                                Tidak Ada Surat
                            @endif
                        </td>
                        <td>{{ $peminjaman->keterangan_peminjaman }}</td>
                        <td>{{ $peminjaman->tanggal_peminjaman }}</td>
                        <td>{{ $peminjaman->jam_mulai }}</td>
                        <td>{{ $peminjaman->jam_selesai }}</td>
                        <td>
                            @if($peminjaman->status == 'sedang diajukan')
                                <span class="badge bg-warning">Sedang Diajukan</span>
                            @elseif($peminjaman->status == 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($peminjaman->status == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @else
                                <span class="badge bg-secondary">{{ $peminjaman->status }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection