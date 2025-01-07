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

        <!-- Tabel daftar peminjaman -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID Peminjam</th>
                    <th>Surat Peminjaman</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Inventaris</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjaman as $item)
                    <tr>
                        <td>{{ $item->id_peminjam }}</td>
                        <td><a href="{{ asset('storage/' . $item->surat_peminjaman) }}" target="_blank">Lihat Surat</a></td>
                        <td>{{ $item->keterangan_peminjaman ?? '-' }}</td>
                        <td>{{ $item->status }}</td>
                        <td>
                            @foreach ($inventaris as $inven)
                                    @if ($inven->id_inventaris == $item->id_inventaris)
                                        <li>{{ $inven->nama_barang }}</li>
                                    @endif
                            @endforeach
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada peminjaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPeminjamanModal">Tambah Peminjaman</button>

        <div class="modal fade" id="addPeminjamanModal" tabindex="-1" aria-labelledby="addPeminjamanModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPeminjamanModalLabel">Tambah Peminjaman Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('hmif.storePemBarang') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="surat_peminjaman" class="form-label">Surat Peminjaman</label>
                                <input type="file" name="surat_peminjaman" id="surat_peminjaman" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan_peminjaman" class="form-label">Keterangan Peminjaman</label>
                                <textarea name="keterangan_peminjaman" id="keterangan_peminjaman" class="form-control" placeholder="Opsional"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="Diajukan" selected>Diajukan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="id_inventaris" class="form-label">Pilih Barang</label>
                                <select name="id_inventaris" id="id_inventaris" class="form-control" required>
                                    <option value="" selected>Pilih barang</option>
                                    @foreach ($inventaris as $item)
                                        <option value="{{ $item->id_inventaris }}">{{ $item->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
