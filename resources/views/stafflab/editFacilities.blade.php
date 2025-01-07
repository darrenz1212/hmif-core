@extends('layouts.starter')

@section('header-content')
    <h3>Edit Fasilitas Ruangan</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <h4>Edit Fasilitas untuk Ruangan: {{ $room->nama_ruangan }}</h4>

        <form action="{{ route('stafflab.updateFacilities', $room->room_id) }}" method="POST">
            @csrf
            @method('PUT')

            <d  iv class="form-group">
                <label for="fasilitas">Fasilitas</label>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Fasilitas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="fasilitas-list">
                        <!-- Fasilitas yang ada sebelumnya -->
                        @foreach($room->fasilitas as $fasilitas)
                        <tr>
                            <td>
                                <input type="text" name="fasilitas[]" class="form-control" value="{{ $fasilitas->nama_barang }}">
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger remove-facility">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Tombol untuk menambah fasilitas baru -->
                <button type="button" class="btn btn-sm btn-success mt-2" id="add-facility">Tambah Fasilitas</button>
            </div>

            <!-- Tombol untuk menyimpan perubahan -->
            <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addFacilityButton = document.getElementById('add-facility');
        const fasilitasList = document.getElementById('fasilitas-list');

        // Fungsi untuk menambah baris baru
        const addFacilityRow = () => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <input type="text" name="fasilitas[]" class="form-control" placeholder="Masukkan fasilitas baru">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-facility">Hapus</button>
                </td>
            `;
            fasilitasList.appendChild(row);

            // Tambahkan event listener untuk tombol hapus pada baris baru
            row.querySelector('.remove-facility').addEventListener('click', function () {
                row.remove();
            });
        };

        // Event listener untuk tombol tambah fasilitas
        if (addFacilityButton && fasilitasList) {
            addFacilityButton.addEventListener('click', addFacilityRow);
        }

        // Event listener untuk tombol hapus pada fasilitas yang sudah ada
        document.querySelectorAll('.remove-facility').forEach(button => {
            button.addEventListener('click', function () {
                button.closest('tr').remove();
            });
        });
    });
</script>
@endsection
