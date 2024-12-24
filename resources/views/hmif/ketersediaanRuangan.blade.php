@extends('layouts.starter')

@section('header-content')
    <h3>Ketersediaan Ruangan</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <h4>Daftar Ruangan Tersedia</h4>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>Nama Ruangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($availableRooms as $room)
                    <tr>
                        <td>{{ $room->nama_ruangan }}</td>
                        <td>
                        <form action="{{ route('submitPengajuanRuangan') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id_ruangan" value="{{ $room->room_id }}">
                            <input type="hidden" name="tanggal_peminjaman" value="{{ $requestData['tanggal_peminjaman'] }}">
                            <input type="hidden" name="jam_mulai" value="{{ $requestData['jam_mulai'] }}">
                            <input type="hidden" name="jam_selesai" value="{{ $requestData['jam_selesai'] }}">
                            <input type="hidden" name="id_peminjam" value="{{ auth()->user()->id }}">

                            <div class="mb-3">
                                <label for="surat_peminjaman" class="form-label">Surat Peminjaman</label>
                                <input type="file" name="surat_peminjaman" id="surat_peminjaman" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="keterangan_peminjaman" class="form-label">Keterangan Peminjaman</label>
                                <textarea name="keterangan_peminjaman" id="keterangan_peminjaman" class="form-control" placeholder="Opsional"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Ajukan</button>
                        </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">Tidak ada ruangan tersedia untuk waktu yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection