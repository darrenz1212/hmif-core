@extends('layouts.starter')

@section('header-content')
    <h3>Manajemen Inventaris</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <!-- Tombol Tambah Inventaris -->
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInventoryModal">Tambah Inventaris</button>
        </div>

        <!-- Tabel Data Inventaris -->
        <h2>Daftar Inventaris</h2>
        <table class="table table-bordered">
            <thead>
            <tr>
                <!-- <th>ID</th> -->
                <th>Nama Barang</th>
                <th>Kondisi</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($inventaris as $i)
                <tr>
                    <!-- <td>{{ $i->id_inventaris }}</td> -->
                    <td>{{ $i->nama_barang }}</td>
                    <td>{{ $i->kondisi }}</td>
                    <td>
                        <!-- Tombol Edit -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editInventoryModal{{ $i->id_inventaris }}">Edit</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Modal Tambah Inventaris -->
        <div class="modal fade" id="addInventoryModal" tabindex="-1" aria-labelledby="addInventoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('inventory.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addInventoryModalLabel">Tambah Inventaris</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama_barang" class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" name="nama_barang" id="nama_barang" required>
                            </div>
                            <div class="mb-3">
                                <label for="kondisi" class="form-label">Kondisi</label>
                                <input type="text" class="form-control" name="kondisi" id="kondisi" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Inventaris -->
        @foreach($inventaris as $i)
            <div class="modal fade" id="editInventoryModal{{ $i->id_inventaris }}" tabindex="-1" aria-labelledby="editInventoryModalLabel{{ $i->id_inventaris }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('inventory.update', $i->id_inventaris) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title" id="editInventoryModalLabel{{ $i->id_inventaris }}">Edit Inventaris</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama_barang{{ $i->id_inventaris }}" class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control" name="nama_barang" id="nama_barang{{ $i->id_inventaris }}" value="{{ $i->nama_barang }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kondisi{{ $i->id_inventaris }}" class="form-label">Kondisi</label>
                                    <input type="text" class="form-control" name="kondisi" id="kondisi{{ $i->id_inventaris }}" value="{{ $i->kondisi }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
