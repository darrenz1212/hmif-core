@extends('layouts.starter')

@section('header-content')
    <h3>Jadwal Ruangan</h3>
@endsection

@section('main-content')
    <div class="row">
        <div class="form-group">
            <label for="room-select">Pilih Ruangan:</label>
            <select id="room-select" class="form-control">
                <option value="">Semua Ruangan</option>
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
            var roomSelect = document.getElementById('room-select');

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
            fetch('/hmif/api/ruangan')
                .then(response => response.json())
                .then(data => {
                    data.forEach(function (room) {
                        var option = document.createElement('option');
                        option.value = room.room_id;
                        option.text = room.nama_ruangan;
                        roomSelect.add(option);
                        if (room.room_id === 1) {
                            roomSelect.value = 1;
                        }
                    });
                    loadEvents(1);
                });

            function loadEvents(roomId) {
                calendar.removeAllEvents();
                var eventsUrl = `/hmif/api/jadwalRuanganByRoom?room_id=${roomId}`;
                fetch(eventsUrl)
                    .then(response => response.json())
                    .then(events => {
                        console.log('Response dari API:', events);
                        events.forEach(event => {
                            calendar.addEvent(event);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching jadwal:', error);
                    });
            }

            roomSelect.addEventListener('change', function () {
                var roomId = this.value;
                loadEvents(roomId);
            });
        });

    </script>
@endsection
