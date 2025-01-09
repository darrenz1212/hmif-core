@extends('layouts.starter')

@section('header-content')
    <h3>Daftar Peminjaman Ruangan</h3>
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
                <th>Nama Ruangan</th>
                <th>Nama Peminjam</th>
                <th>Tanggal Peminjaman</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Surat Peminjaman</th>
                <th>Status</th>
                <th>Feedback</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjamanRuangan as $item)
                <tr>
                    <td>{{ $item->nama_ruangan }}</td>
                    <td>{{ $item->nama_peminjam }}</td>
                    <td>{{ $item->tanggal_peminjaman }}</td>
                    <td>{{ $item->jam_mulai }}</td>
                    <td>{{ $item->jam_selesai }}</td>
                    <td>
                        @if ($item->surat_peminjaman)
                            <a href="{{ asset('storage/' . $item->surat_peminjaman) }}" target="_blank">Lihat Surat</a>
                        @else
                            Tidak Ada Surat
                        @endif
                    </td>
                    <td>
                        @if ($item->status == 'sedang diajukan')
                            <span class="badge bg-warning text-dark">Sedang Diajukan</span>
                        @elseif ($item->status == 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif ($item->status == 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-secondary">Tidak Diketahui</span>
                        @endif
                    </td>
                    <td>{{ $item->feedback ?? 'Belum Ada Feedback' }}</td>

                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Modal untuk tambah peminjaman -->
    <div class="modal fade" id="addPeminjamanModal" tabindex="-1" aria-labelledby="addPeminjamanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPeminjamanModalLabel">Tambah Peminjaman Ruangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formPeminjaman" action="{{ route('submitPengajuanRuangan') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-body">
                    <div class="modal-body">
    <!-- Input untuk Tanggal dan Jam -->
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

    <!-- Tombol untuk Cek Ketersediaan -->
    <button type="button" id="cekKetersediaanBtn" class="btn btn-primary w-100">Cek Ketersediaan</button>

    <!-- Field untuk Memilih Ruangan -->
    <div class="mb-3 d-none" id="pilihRuanganWrapper">
        <label for="id_ruangan" class="form-label">Pilih Ruangan</label>
        <select name="id_ruangan" id="id_ruangan" class="form-control" required></select>
    </div>

    <!-- Input untuk Keterangan -->
    <div class="mb-3">
        <label for="keterangan_peminjaman" class="form-label">Keterangan Peminjaman</label>
        <textarea name="keterangan_peminjaman" id="keterangan_peminjaman" class="form-control" placeholder="Opsional"></textarea>
    </div>

    <!-- Input untuk Surat Peminjaman -->
    <div class="mb-3">
        <label for="surat_peminjaman" class="form-label">Surat Peminjaman</label>
        <input type="file" name="surat_peminjaman" id="surat_peminjaman" class="form-control" required>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
</div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('cekKetersediaanBtn').addEventListener('click', function () {
        const tanggal = document.getElementById('tanggal_peminjaman').value;
        const jamMulai = document.getElementById('jam_mulai').value;
        const jamSelesai = document.getElementById('jam_selesai').value;

        // Validasi input
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

        fetch('{{ route("checkRoomAvailability") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ tanggal_peminjaman: tanggal, jam_mulai: jamMulai, jam_selesai: jamSelesai })
        })
            .then(response => response.json())
            .then(data => {
                const selectRuangan = document.getElementById('id_ruangan');
                selectRuangan.innerHTML = ''; // Kosongkan daftar ruangan sebelumnya

                if (data.available) {
                    data.ruangan.forEach(ruangan => {
                        const option = document.createElement('option');
                        option.value = ruangan.room_id;
                        option.textContent = ruangan.nama_ruangan;
                        selectRuangan.appendChild(option);
                    });

                    // Tampilkan field "Pilih Ruangan"
                    document.getElementById('pilihRuanganWrapper').classList.remove('d-none');
                } else {
                    alert('Tidak ada ruangan yang tersedia pada waktu tersebut.');
                    document.getElementById('pilihRuanganWrapper').classList.add('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memeriksa ketersediaan.');
            });
    });

</script>
@endsection
