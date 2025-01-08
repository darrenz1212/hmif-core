@extends('layouts.starter')

@section('header-content')
    <h3>List Pengajuan</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <h4>Daftar Pengajuan Ruangan</h4>
        <table class="table table-bordered table-striped mt-3">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                <th>Aksi</th>
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
                            Tidak Ada Pengajuan
                        @endif
                    </td>
                    <td>{{ $peminjaman->keterangan_peminjaman }}</td>
                    <td>{{ $peminjaman->tanggal_peminjaman }}</td>
                    <td>{{ $peminjaman->jam_mulai }}</td>
                    <td>{{ $peminjaman->jam_selesai }}</td>
                    <td>
                        @if($peminjaman->status == 'sedang diajukan')
                            <span class="badge bg-warning">Diajukan</span>
                        @elseif($peminjaman->status == 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif($peminjaman->status == 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-secondary">{{ $peminjaman->status }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-around">
                            <button class="btn btn-success btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#approveModal-{{ $peminjaman->id_peminjaman_ruangan }}">
                                <i class="bi bi-check-circle me-1"></i> Setujui
                            </button>


                            <button class="btn btn-danger btn-sm d-flex align-items-center">
                                <i class="bi bi-x-circle me-1"></i> Tolak
                            </button>
                        </div>
                    </td>

                </tr>

                <div class="modal fade" id="approveModal-{{ $peminjaman->id_peminjaman_ruangan }}" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('peminjaman.approve', $peminjaman->id_peminjaman_ruangan) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="approveModalLabel">Setujui Peminjaman</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menyetujui peminjaman ini?</p>
                                    <label for="feedback-{{ $peminjaman->id_peminjaman_ruangan }}">Feedback:</label>
                                    <textarea name="feedback" id="feedback-{{ $peminjaman->id_peminjaman_ruangan }}" class="form-control" rows="3" required>Ruangan sudah disetujui. Silahkan pakai di waktu yang sudah ditentukan.</textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success">Setujui</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @endforeach
            </tbody>
        </table>
    </div>
@endsection


