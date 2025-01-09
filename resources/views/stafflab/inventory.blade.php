@extends('layouts.starter')

@section('header-content')
    <h3>Data Inventaris</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <h1>Daftar Inventaris</h1>

        <!-- Tombol Tambah Data -->
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Data</button>

        <!-- Tabel Inventaris -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Kondisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventaris as $item)
                    <tr>
                        <td>{{ $item->id_inventaris }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->kondisi }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-warning btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal" 
                                    data-id="{{ $item->id_inventaris }}" 
                                    data-nama="{{ $item->nama_barang }}" 
                                    data-kondisi="{{ $item->kondisi }}">
                                Edit
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data inventaris</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('stafflab.storeInventory') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Tambah Inventaris</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="addNama" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="addNama" name="nama_barang" required>
                        </div>
                        <div class="mb-3">
                            <label for="addKondisi" class="form-label">Kondisi</label>
                            <select class="form-select" id="addKondisi" name="kondisi" required>
                                <option value="Baik">Baik</option>
                                <option value="Rusak">Rusak</option>
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

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('stafflab.updateInventory') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Inventaris</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_inventaris" id="editId">

                        <div class="mb-3">
                            <label for="editNama" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="editNama" name="nama_barang" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editKondisi" class="form-label">Kondisi</label>
                            <select class="form-select" id="editKondisi" name="kondisi" required>
                                <option value="Baik">Baik</option>
                                <option value="Rusak">Rusak</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    // Isi data modal edit saat tombol Edit diklik
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Tombol yang diklik
        const id = button.getAttribute('data-id'); // Ambil ID
        const nama = button.getAttribute('data-nama'); // Ambil Nama Barang
        const kondisi = button.getAttribute('data-kondisi'); // Ambil Kondisi

        // Masukkan data ke dalam modal
        const modalId = editModal.querySelector('#editId');
        const modalNama = editModal.querySelector('#editNama');
        const modalKondisi = editModal.querySelector('#editKondisi');

        modalId.value = id;       // Isi hidden input untuk ID
        modalNama.value = nama;   // Isi field Nama Barang
        modalKondisi.value = kondisi; // Isi dropdown Kondisi
    });
</script>
@endsection
