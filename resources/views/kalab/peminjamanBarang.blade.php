@extends('layouts.starter')

@section('header-content')
    <h3>Daftar Peminjaman Barang</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <!-- Alert pesan sukses -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabel daftar peminjaman -->
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Inventaris</th>
                <th>Keterangan</th>
                <th>Surat Peminjaman</th>
                <th>Tanggal Peminjaman</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($peminjaman as $item)
                <tr>
                    <td>
                        @foreach ($inventaris as $inven)
                            @if ($inven->id_inventaris == $item->id_inventaris)
                                {{ $inven->nama_barang }}
                            @endif
                        @endforeach
                    </td>
                    <td>{{ $item->keterangan_peminjaman ?? '-' }}</td>
                    <td><a href="{{ asset('storage/' . $item->surat_peminjaman) }}" target="_blank">Lihat Surat</a></td>
                    <td>{{ $item->tanggal_peminjaman }}</td>
                    <td>{{ $item->jam_mulai }}</td>
                    <td>{{ $item->jam_selesai }}</td>
                    <td>
                        @if ($item->status == 'diajukan')
                            <span class="badge bg-warning text-dark">Sedang Diajukan</span>
                        @elseif ($item->status == 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif ($item->status == 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-secondary">Tidak Diketahui</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-around">
                            <button class="btn btn-success btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#approveModal-{{ $item->id_peminjaman_inventaris }}">
                                <i class="bi bi-check-circle me-1"></i> Setujui
                            </button>

                            <button class="btn btn-danger btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#declineModal-{{ $item->id_peminjaman_inventaris }}">
                                <i class="bi bi-x-circle me-1"></i> Tolak
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada peminjaman.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        @foreach ($peminjaman as $item)
            <div class="modal fade" id="approveModal-{{ $item->id_peminjaman_inventaris }}" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="approveModalLabel">Setujui Peminjaman</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('kalab.approvedPengajuanBarang', $item->id_peminjaman_inventaris) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin menyetujui peminjaman <strong>{{ $item->keterangan_peminjaman ?? 'tanpa keterangan' }}</strong>?</p>

                                <!-- Textarea untuk feedback -->
                                <div class="form-group mt-3">
                                    <label for="feedback">Feedback:</label>
                                    <textarea name="feedback" id="feedback" class="form-control" rows="3">Peminjaman barang sudah disetujui, mohon gunakan barang dengan bertanggung jawab.</textarea>
                                </div>
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


        <!-- Modal Tolak -->
        @foreach ($peminjaman as $item)
            <div class="modal fade" id="declineModal-{{ $item->id_peminjaman_inventaris }}" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="declineModalLabel">Tolak Peminjaman</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('kalab.declinePengajuanBarang', $item->id_peminjaman_inventaris) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin menolak peminjaman <strong>{{ $item->keterangan_peminjaman ?? 'tanpa keterangan' }}</strong>?</p>

                                <!-- Textarea untuk feedback -->
                                <div class="form-group mt-3">
                                    <label for="feedback-decline-{{ $item->id_peminjaman_inventaris }}">Feedback:</label>
                                    <textarea name="feedback" id="feedback-decline-{{ $item->id_peminjaman_inventaris }}" class="form-control" rows="3" ></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Tolak</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection
