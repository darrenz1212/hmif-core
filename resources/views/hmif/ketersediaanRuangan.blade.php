@extends('layouts.starter')

@section('header-content')
    <h3>Ketersediaan Ruangan</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <h4>Daftar Ruangan Tersedia</h4>
        <table class="table table-bordered table-striped mt-3">
            <thead>
            <tr>
                <th>Nama Ruangan</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($availableRooms as $room)
                <tr>
                    <td>{{ $room->nama_ruangan }}</td>
                    <td>
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#infoModal"
                                onclick="loadRoomInfo({{ $room->room_id }}, '{{ $requestData['tanggal_peminjaman'] }}', '{{ $requestData['waktu_peminjaman'] }}', '{{ $requestData['tanggal_batas'] }}', '{{ $requestData['waktu_batas'] }}')">
                            Info
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="infoModalLabel">Informasi Ruangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Dynamic Content -->
                        <p><strong>Nama Ruangan:</strong> <span id="modalNamaRuangan"></span></p>
                        <p><strong>Kapasitas:</strong> <span id="modalKapasitas"></span></p>
                        <p><strong>Fasilitas:</strong></p>
                        <ul id="modalFasilitas"></ul>

                        <!-- Form Peminjaman -->
                        <form action="{{ route('submitPengajuanRuangan') }}" method="POST" id="pinjamRuanganForm">
                            @csrf
                            <input type="hidden" name="id_ruangan" id="modalRoomId">
                            <input type="hidden" name="id_peminjam" value="{{ auth()->user()->id }}">
                            <input type="hidden" name="tanggal_peminjaman" id="modalTanggalPeminjaman">
                            <input type="hidden" name="waktu_peminjaman" id="modalWaktuPeminjaman">
                            <input type="hidden" name="tanggal_batas" id="modalTanggalBatas">
                            <input type="hidden" name="waktu_batas" id="modalWaktuBatas">

                            <div class="mb-3">
                                <label for="keterangan_peminjaman" class="form-label">Keterangan Peminjaman</label>
                                <input type="text" class="form-control" name="keterangan_peminjaman" id="keterangan_peminjaman" placeholder="Opsional">
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Pinjam Ruangan</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function loadRoomInfo(roomId, tanggalPeminjaman, waktuPeminjaman, tanggalBatas, waktuBatas) {
        // Fetch room info
        fetch(`/hmif/ketersediaanRuangan/${roomId}/info`)
            .then(response => response.json())
            .then(data => {
                // Populate modal fields
                document.getElementById('modalNamaRuangan').innerText = data.nama_ruangan;
                document.getElementById('modalKapasitas').innerText = data.kapasitas + " Orang";

                // Populate fasilitas
                const fasilitasList = document.getElementById('modalFasilitas');
                fasilitasList.innerHTML = '';
                data.fasilitas.forEach(fasilitas => {
                    const li = document.createElement('li');
                    li.innerText = fasilitas.nama_barang;
                    fasilitasList.appendChild(li);
                });

                // Set form values
                document.getElementById('modalRoomId').value = roomId;
                document.getElementById('modalTanggalPeminjaman').value = tanggalPeminjaman;
                document.getElementById('modalWaktuPeminjaman').value = waktuPeminjaman;
                document.getElementById('modalTanggalBatas').value = tanggalBatas;
                document.getElementById('modalWaktuBatas').value = waktuBatas;
            })
            .catch(error => console.error('Error:', error));
    }
</script>
