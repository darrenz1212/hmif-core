@extends('layouts.starter')

@section('header-content')
    <h3>Pendataan Ruangan</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <h4>Daftar Ruangan</h4>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>Nama Ruangan</th>
                    <th>Kapasitas</th>
                    <th>Ketersediaan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ruangan as $room)
                    <tr>
                        <td>{{ $room->nama_ruangan }}</td>
                        <td>{{ $room->kapasitas }}</td>
                        <td>{{ $room->ketersediaan ? 'Tersedia' : 'Tidak Tersedia' }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editRoomModal" 
                                data-room-id="{{ $room->room_id }}" 
                                data-room-name="{{ $room->nama_ruangan }}" 
                                data-room-capacity="{{ $room->kapasitas }}" 
                                data-room-availability="{{ $room->ketersediaan }}">
                                Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoomModal">Tambah Ruangan</button>
        </div>
    </div>

    <!-- Modal Tambah Ruangan -->
    <div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRoomModalLabel">Tambah Ruangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createRoomForm" method="POST" action="{{ route('stafflab.storeRooms') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_ruangan" class="form-label">Nama Ruangan</label>
                            <input type="text" class="form-control" id="nama_ruangan" name="nama_ruangan" required>
                        </div>
                        <div class="mb-3">
                            <label for="kapasitas" class="form-label">Kapasitas</label>
                            <input type="number" class="form-control" id="kapasitas" name="kapasitas" required>
                        </div>
                        <div class="mb-3">
                            <label for="ketersediaan" class="form-label">Ketersediaan</label>
                            <select class="form-select" id="ketersediaan" name="ketersediaan" required>
                                <option value="1">Tersedia</option>
                                <option value="0">Tidak Tersedia</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Ruangan -->
    <div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoomModalLabel">Edit Ruangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editRoomForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_nama_ruangan" class="form-label">Nama Ruangan</label>
                            <input type="text" class="form-control" id="edit_nama_ruangan" name="nama_ruangan" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_kapasitas" class="form-label">Kapasitas</label>
                            <input type="number" class="form-control" id="edit_kapasitas" name="kapasitas" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_ketersediaan" class="form-label">Ketersediaan</label>
                            <select class="form-select" id="edit_ketersediaan" name="ketersediaan" required>
                                <option value="1">Tersedia</option>
                                <option value="0">Tidak Tersedia</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editRoomModal = document.getElementById('editRoomModal');
            editRoomModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const roomId = button.getAttribute('data-room-id');
                const roomName = button.getAttribute('data-room-name');
                const roomCapacity = button.getAttribute('data-room-capacity');
                const roomAvailability = button.getAttribute('data-room-availability');

                const form = document.getElementById('editRoomForm');
                form.action = `/stafflab/rooms/${roomId}`;

                document.getElementById('edit_nama_ruangan').value = roomName;
                document.getElementById('edit_kapasitas').value = roomCapacity;
                document.getElementById('edit_ketersediaan').value = roomAvailability;
            });
        });
    </script>
@endsection