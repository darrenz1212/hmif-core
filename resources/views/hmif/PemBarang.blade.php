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
                <th>Feedback</th>
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
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $item->id_peminjaman_inventaris }}">
                            Lihat Feedback
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="feedbackModal{{ $item->id_peminjaman_inventaris }}" tabindex="-1" aria-labelledby="feedbackModalLabel{{ $item->id_peminjaman_inventaris }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="feedbackModalLabel{{ $item->id_peminjaman_inventaris }}">Feedback</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{ $item->feedback ?? 'Belum Ada Feedback' }}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                        <div id="alert-container"></div>
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
                        <div class="mb-3 d-none" id="availableItems">
                            <label for="id_inventaris" class="form-label">Pilih Barang</label>
                            <select name="id_inventaris" id="id_inventaris" class="form-control" required></select>
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

<script>
    document.getElementById('checkAvailability').addEventListener('click', function () {
        const tanggal = document.getElementById('tanggal_peminjaman').value;
        const jamMulai = document.getElementById('jam_mulai').value;
        const jamSelesai = document.getElementById('jam_selesai').value;

        // Fungsi untuk menampilkan alert Bootstrap
        function showAlert(message, type = 'warning') {
            const alertContainer = document.getElementById('alert-container');
            alertContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        }

        if (!tanggal || !jamMulai || !jamSelesai) {
            showAlert('Harap lengkapi semua field sebelum cek ketersediaan.');
            return;
        }

        const today = new Date();
        const selectedDate = new Date(tanggal);
        const dayOfWeek = selectedDate.getDay();
        today.setHours(0, 0, 0, 0);
        const minDate = new Date(today);
        minDate.setDate(today.getDate() + 3);

        if (dayOfWeek === 0 || dayOfWeek === 6) {
            showAlert('Peminjaman hanya diperbolehkan pada hari Senin-Jumat.');
            return;
        }
        
        if (selectedDate < minDate) {
            showAlert('Tanggal peminjaman paling cepat H+3 dari hari ini.');
            return;
        }

        if (jamMulai < '07:00' || jamSelesai > '21:00') {
            showAlert('Peminjaman hanya diperbolehkan pada pukul 07.00 - 21.00.');
            return;
        }


        if (jamSelesai <= jamMulai) {
            showAlert('Jam selesai harus lebih besar dari jam mulai.');
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

                if (data.length > 0) {
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id_inventaris;
                        option.textContent = item.nama_barang;
                        select.appendChild(option);
                    });
                    document.getElementById('availableItems').classList.remove('d-none');
                    showAlert('Barang tersedia untuk waktu yang dipilih!', 'success');
                } else {
                    showAlert('Tidak ada barang yang tersedia pada waktu tersebut.');
                    document.getElementById('availableItems').classList.add('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat memeriksa ketersediaan.', 'danger');
            });
    });
</script>
@endsection
