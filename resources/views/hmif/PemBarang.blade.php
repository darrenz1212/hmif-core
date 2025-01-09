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

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPeminjamanModal">Tambah Peminjaman</button>
        <br><br>

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
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada peminjaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Modal untuk tambah peminjaman -->
        <div class="modal fade" id="addPeminjamanModal" tabindex="-1" aria-labelledby="addPeminjamanModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPeminjamanModalLabel">Tambah Peminjaman Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formPeminjaman" action="{{ route('hmif.storePemBarang') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="modal-body">
                            <!-- Step 1: Pilih Tanggal dan Waktu -->
                            <div class="mb-3">
                                <label for="tanggal_peminjaman" class="form-label">Tanggal Peminjaman</label>
                                <input type="date" name="tanggal_peminjaman" id="tanggal_peminjaman" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="jam_selesai" class="form-label">Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
                            </div>
                            <button type="button" id="checkAvailability" class="btn btn-primary">Cek Ketersediaan Barang</button>

                            <!-- Step 2: Pilih Barang yang Tersedia -->
                            <div class="mb-3" id="availableItems" style="display: none;">
                                <label for="id_inventaris" class="form-label">Pilih Barang</label>
                                <select name="id_inventaris" id="id_inventaris" class="form-control" required>
                                    <!-- Barang yang tersedia akan diisi oleh JavaScript -->
                                </select>
                            </div>

                            <!-- Keterangan Peminjaman -->
                            <div class="mb-3">
                                <label for="keterangan_peminjaman" class="form-label">Keterangan Peminjaman</label>
                                <textarea name="keterangan_peminjaman" id="keterangan_peminjaman" class="form-control" placeholder="Opsional"></textarea>
                            </div>

                            <!-- Upload Surat Peminjaman -->
                            <div class="mb-3">
                                <label for="surat_peminjaman" class="form-label">Surat Peminjaman</label>
                                <input type="file" name="surat_peminjaman" id="surat_peminjaman" class="form-control" required>
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

    <!-- JavaScript untuk Cek Ketersediaan Barang -->
    <script>
        document.getElementById('checkAvailability').addEventListener('click', function () {
            const tanggal = document.getElementById('tanggal_peminjaman').value;
            const jamMulai = document.getElementById('jam_mulai').value;
            const jamSelesai = document.getElementById('jam_selesai').value;

            if (!tanggal || !jamMulai || !jamSelesai) {
                alert('Harap lengkapi semua field sebelum cek ketersediaan.');
                return;
            }

            const today = new Date();
            const selectedDate = new Date(tanggal);
            today.setHours(0, 0, 0, 0); // Set waktu ke 00:00 untuk hari ini
            const minDate = new Date(today);
            minDate.setDate(today.getDate() + 3); // H+3

            if (selectedDate < minDate) {
                alert('Tanggal peminjaman paling cepat H+3 dari hari ini.');
                return;
            }

            // Validasi Jam Selesai harus lebih besar dari Jam Mulai
            if (jamSelesai <= jamMulai) {
                alert('Jam selesai harus lebih besar dari jam mulai.');
                return;
            }

            fetch('{{ url('/checkAvailability') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ tanggal, jamMulai, jamSelesai }),
            })
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('id_inventaris');
                    select.innerHTML = '';

                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id_inventaris;
                        option.textContent = item.nama_barang;
                        select.appendChild(option);
                    });

                    document.getElementById('availableItems').style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
@endsection
