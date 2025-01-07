@extends('layouts.starter')

@section('header-content')
    <h3>{{ $room->nama_ruangan }}</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Fasilitas</th>
                <th>Kondisi</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody id="fasilitas-list">
            <!-- Fasilitas yang ada sebelumnya -->
            @foreach($room->fasilitas as $fasilitas)
                <tr>
                    <td>
                        <p>{{ $fasilitas->nama_barang }}</p>
                    </td>
                    <td>
                        <p>{{ $fasilitas->kondisi_barang }}</p>
                    </td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editFacilityModal"
                                data-id="{{ $fasilitas->id_fasilitas }}" data-nama="{{ $fasilitas->nama_barang }}"
                                data-kondisi="{{ $fasilitas->kondisi_barang }}">
                            Edit
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Tombol untuk menambah fasilitas baru -->
        <button type="button" class="btn btn-sm btn-success mt-2" data-bs-toggle="modal" data-bs-target="#addFacilityModal">
            Tambah Fasilitas
        </button>
    </div>

    <!-- Modal Tambah Fasilitas -->
    <div class="modal fade" id="editFacilityModal" tabindex="-1" aria-labelledby="editFacilityModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFacilityModalLabel">Edit Fasilitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="editFacilityForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_nama_barang">Nama Barang</label>
                            <input type="text" name="nama_barang" id="edit_nama_barang" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_kondisi_barang">Kondisi Barang</label>
                            <select name="kondisi_barang" id="edit_kondisi_barang" class="form-select" required>
                                <option value="Bagus">Bagus</option>
                                <option value="Rusak">Rusak</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Fasilitas -->
    <div class="modal fade" id="editFacilityModal" tabindex="-1" aria-labelledby="editFacilityModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFacilityModalLabel">Edit Fasilitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('stafflab.updateFacilities',$fasilitas->id_fasilitas) }}" method="POST" id="editFacilityForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">

                            <label for="edit_nama_barang">Nama Barang</label>
                            <input type="text" name="nama_barang" id="edit_nama_barang" class="form-control" value="{{ $fasilitas->id_fasilitas }}" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_kondisi_barang">Kondisi Barang</label>
                            <select name="kondisi_barang" id="edit_kondisi_barang" class="form-select" required>
                                <option value="Bagus" style="color: green;" {{ $fasilitas->kondisi_barang == 'Bagus' ? 'selected' : '' }}>Bagus</option>
                                <option value="Rusak" style="color: red;" {{ $fasilitas->kondisi_barang == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editFacilityModal = document.getElementById('editFacilityModal');

            editFacilityModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-nama');
                const kondisi = button.getAttribute('data-kondisi');

                const form = document.getElementById('editFacilityForm');
                form.action = `/stafflab/roomsFacilities/${id}`;
                document.getElementById('edit_nama_barang').value = nama;
                document.getElementById('edit_kondisi_barang').value = kondisi;
            });
        });
    </script>
@endsection


