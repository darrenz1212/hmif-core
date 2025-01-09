@extends('layouts.starter')

@section('header-content')
    <h3>Jadwal Ruangan</h3>
@endsection

@section('main-content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row">
        <div class="form-group">
            <label for="room-select">Pilih Ruangan:</label>
            <select id="room-select" class="form-control">
                <option value="">Pilih Ruangan</option>
            </select>
        </div>
    </div>
    <span style="visibility: hidden; height: 5px">.</span>
    <div class="row">
        <div class="col text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addJadwalModal">
                Tambah Jadwal
            </button>
        </div>
    </div>

    <div class="modal fade" id="addJadwalModal" tabindex="-1" aria-labelledby="addJadwalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('jadwalRuangan.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addJadwalModalLabel">Tambah Jadwal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="room_id" class="form-label">Ruangan</label>
                            <select name="room_id" id="room-select-add" class="form-control" required>
                                <option value="" >Pilih Ruangan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="jam_mulai" class="form-label">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="jam_selesai" class="form-label">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-check">
                            <input type="hidden" name="isRepeat" value="0">
                            <input type="checkbox" name="isRepeat" id="isRepeat" class="form-check-input" value="1">
                            <label for="isRepeat" class="form-check-label">
                                Tambahkan jadwal di waktu yang sama selama 6 bulan ke depan
                            </label>
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

    <span style="visibility: hidden; height: 20px">.</span>
    <div class="row">
        <div id="calendar"></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var roomSelect = document.getElementById('room-select');
            var roomSelectAdd = document.getElementById('room-select-add');

            // Inisialisasi FullCalendar
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek', // Tampilan default adalah minggu dengan grid waktu
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                slotMinTime: '07:00:00',
                slotMaxTime: '21:00:00',
                events: [],
                eventClick: function (info) {
                    console.log(info)
                    Swal.fire({
                        title: info.event.title || 'No Title',
                        html: `
                <p><strong>Start:</strong> ${info.event.start.toLocaleString()}</p>
                <p><strong>End:</strong> ${info.event.end ? info.event.end.toLocaleString() : 'No End Time'}</p>
            `,
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                }
            });


            calendar.render();

            // Load data ruangan untuk dropdown
            fetch('/klb/api/ruangan')
                .then(response => response.json())
                .then(data => {
                    data.forEach(function (room) {
                        // Tambahkan ke dropdown menampilkan jadwal
                        var optionView = document.createElement('option');
                        optionView.value = room.room_id;
                        optionView.text = room.nama_ruangan;
                        roomSelect.add(optionView);

                        // Tambahkan ke dropdown modal tambah jadwal
                        var optionAdd = document.createElement('option');
                        optionAdd.value = room.room_id;
                        optionAdd.text = room.nama_ruangan;
                        roomSelectAdd.add(optionAdd);
                    });

                    // Atur nilai default untuk dropdown menampilkan jadwal
                    roomSelect.value = data.length > 0 ? data[0].room_id : "";
                    loadEvents(roomSelect.value);

                    // Atur dropdown tambah jadwal agar sinkron dengan pilihan ruangan yang ditampilkan
                    roomSelectAdd.value = roomSelect.value;
                });

            // Sinkronkan dropdown tambah jadwal dengan pilihan ruangan
            roomSelect.addEventListener('change', function () {
                var selectedRoom = this.value;
                roomSelectAdd.value = selectedRoom;
                loadEvents(selectedRoom); // Muat ulang jadwal untuk ruangan yang dipilih
            });

            function loadEvents(roomId) {
                if (!roomId) return;
                calendar.removeAllEvents();
                var eventsUrl = `/klb/api/jadwalRuanganByRoom?room_id=${roomId}`;
                fetch(eventsUrl)
                    .then(response => response.json())
                    .then(events => {
                        events.forEach(event => {
                            calendar.addEvent(event);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching jadwal:', error);
                    });
            }
        });

    </script>
@endsection
