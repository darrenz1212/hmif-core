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
                                onclick="loadRoomInfo({{ $room->room_id }})">
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
    function loadRoomInfo(roomId) {
        console.log(roomId)
        fetch(`/hmif/ketersediaanRuangan/${roomId}/info`)
            .then(response => response.json())
            .then(data => {
                // Isi modal dengan data ruangan
                document.getElementById('modalNamaRuangan').innerText = data.nama_ruangan;
                document.getElementById('modalKapasitas').innerText = data.kapasitas + " Orang";

                // Kosongkan daftar fasilitas
                const fasilitasList = document.getElementById('modalFasilitas');
                fasilitasList.innerHTML = '';
                data.fasilitas.forEach(fasilitas => {
                    const li = document.createElement('li');
                    li.innerText = fasilitas.nama_barang;
                    fasilitasList.appendChild(li);
                });
            })
            .catch(error => console.error('Error:', error));
    }
</script>
