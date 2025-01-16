@extends('layouts.starter')

@section('header-content')
    <h3>Manajemen Ruangan</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <!-- Alert untuk Pesan Sukses atau Error -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Tombol Tambah Ruangan -->
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">Tambah Ruangan</button>
        </div>

        <!-- Tabel Data Ruangan -->
        <h2>Daftar Ruangan</h2>
        <table class="table table-bordered">
            <thead>
            <tr>
                <!-- <th>No</th> -->
                <th>Nama Ruangan</th>
                <th>Kapasitas</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($ruangan as $room)
                <tr>
                    <!-- <td>{{ $room->room_id }}</td> -->
                    <td>{{ $room->nama_ruangan }}</td>
                    <td>{{ $room->kapasitas }} orang</td>
                    <td>
                        <!-- Tombol Edit -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRoomModal{{ $room->room_id }}">Edit</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Modal Tambah Ruangan -->
        <div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('rooms.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addRoomModalLabel">Tambah Ruangan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama_ruangan" class="form-label">Nama Ruangan</label>
                                <input type="text" class="form-control" name="nama_ruangan" id="nama_ruangan" required>
                            </div>
                            <div class="mb-3">
                                <label for="kapasitas" class="form-label">Kapasitas</label>
                                <input type="number" class="form-control" name="kapasitas" id="kapasitas" required>
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

        <!-- Modal Edit Ruangan -->
        @foreach($ruangan as $room)
            <div class="modal fade" id="editRoomModal{{ $room->room_id }}" tabindex="-1" aria-labelledby="editRoomModalLabel{{ $room->room_id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('rooms.update', $room->room_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title" id="editRoomModalLabel{{ $room->room_id }}">Edit Ruangan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama_ruangan{{ $room->room_id }}" class="form-label">Nama Ruangan</label>
                                    <input type="text" class="form-control" name="nama_ruangan" id="nama_ruangan{{ $room->room_id }}" value="{{ $room->nama_ruangan }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kapasitas{{ $room->room_id }}" class="form-label">Kapasitas</label>
                                    <input type="number" class="form-control" name="kapasitas" id="kapasitas{{ $room->room_id }}" value="{{ $room->kapasitas }}" required>
                                </div>
                                <input type="hidden" name="ketersediaan" id="ketersediaan{{ $room->room_id }}" value="1">
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
