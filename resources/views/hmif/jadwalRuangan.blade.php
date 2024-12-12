@extends('layouts.starter')

@section('header-content')
    <h3>Jadwal Ruangan</h3>
@endsection

@section('main-content')
    <div class="row">
        <div class="form-group">
            <label for="room-select">Pilih Ruangan:</label>
            <select id="room-select" class="form-control">
                <option value="">Semua Ruangan</option> <!-- Default pilihan -->
            </select>
        </div>
    </div>
    <span style="visibility: hidden; height: 20px">.</span>
    <div class="row">
        <div id="calendar"></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: '/hmif/api/jadwalRuangan', // Default semua jadwal
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                eventClick: function (info) {
                    alert('Event: ' + info.event.title + '\n' +
                        'Start: ' + info.event.start + '\n' +
                        'End: ' + info.event.end + '\n' +
                        'Description: ' + (info.event.extendedProps.description || 'No description'));
                }
            });

            calendar.render();

            // Load data ruangan untuk dropdown
            fetch('/hmif/api/ruangan')
                .then(response => response.json())
                .then(data => {
                    var roomSelect = document.getElementById('room-select');
                    data.forEach(function (room) {
                        var option = document.createElement('option');
                        option.value = room.room_id; // room_id dari database
                        option.text = room.nama_ruangan; // nama_ruangan dari database
                        roomSelect.add(option);
                    });
                });

            // Filter kalender berdasarkan ruangan
            document.getElementById('room-select').addEventListener('change', function () {
                var roomId = this.value; // Ambil room_id dari dropdown

                // Update jadwal berdasarkan ruangan yang dipilih
                calendar.removeAllEvents(); // Hapus semua event sebelumnya
                var eventsUrl = roomId ? `/hmif/api/jadwalRuanganByRoom?room_id=${roomId}` : '/hmif/api/jadwalRuangan';
                fetch(eventsUrl)
                    .then(response => response.json())
                    .then(events => {
                        events.forEach(event => {
                            calendar.addEvent(event); // Tambahkan event ke kalender
                        });
                    });
            });
        });
    </script>
@endsection
