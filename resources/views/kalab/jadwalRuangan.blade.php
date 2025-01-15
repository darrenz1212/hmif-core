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

    <div class="modal fade" id="editJadwalModal" tabindex="-1" aria-labelledby="editJadwalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editJadwalForm" action="{{ route('edit.jadwal', ':id') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editJadwalModalLabel">Edit Jadwal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-room-select" class="form-label">Ruangan</label>
                            <select name="room_id" id="edit-room-select" class="form-control" required>
                                <option value="">Pilih Ruangan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-tanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" id="edit-tanggal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-jam-mulai" class="form-label">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="edit-jam-mulai" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-jam-selesai" class="form-label">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="edit-jam-selesai" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="edit-keterangan" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-check">
                            <input type="hidden" name="isRepeat" value="0">
                            <input type="checkbox" name="isRepeat" id="edit-isRepeat" class="form-check-input" value="1">
                            <label for="edit-isRepeat" class="form-check-label"></label>
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
                    console.log("event.id : ", info.event.id)
                    fetch(`/jadwalRuangan/${info.event.id}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data) {
                                Swal.fire({
                                    title: info.event.title || 'No Title',
                                    html: `
                        <p><strong>Start:</strong> ${info.event.start.toLocaleString()}</p>
                        <p><strong>End:</strong> ${info.event.end ? info.event.end.toLocaleString() : 'No End Time'}</p>
                        <button id="editJadwalBtn" class="btn btn-primary">Edit Jadwal</button>
                    `,
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    didOpen: () => {
                                        // Tambahkan event listener untuk tombol edit jadwal
                                        const editBtn = document.getElementById('editJadwalBtn');
                                        editBtn.addEventListener('click', () => {
                                            Swal.close(); // Tutup SweetAlert
                                            openEditModal(data); // Buka modal edit dengan data jadwal
                                        });
                                    }
                                });
                            } else {
                                Swal.fire('Error', 'Jadwal tidak ditemukan', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching jadwal:', error);
                            Swal.fire('Error', 'Gagal memuat data jadwal', 'error');
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

            function openEditModal(data) {
                // Ambil dropdown ruangan di modal
                const roomSelect = document.getElementById('edit-room-select');

                // Kosongkan dropdown sebelum memuat data baru
                roomSelect.innerHTML = '<option value="">Pilih Ruangan</option>';

                // Fetch data ruangan
                fetch('/klb/api/ruangan')
                    .then(response => response.json())
                    .then(rooms => {
                        // Tambahkan setiap ruangan ke dropdown
                        rooms.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room.room_id; // Ganti `id` sesuai kolom primary key ruangan
                            option.textContent = room.nama_ruangan; // Ganti `nama_ruangan` sesuai kolom nama ruangan
                            roomSelect.appendChild(option);
                        });

                        // Set nilai ruangan yang sesuai dengan data jadwal
                        roomSelect.value = data.room_id;

                        // Isi data lainnya ke dalam modal
                        document.getElementById('edit-tanggal').value = data.tanggal;
                        document.getElementById('edit-jam-mulai').value = data.jam_mulai;
                        document.getElementById('edit-jam-selesai').value = data.jam_selesai;
                        document.getElementById('edit-keterangan').value = data.keterangan;

                        // Periksa apakah ada jadwal berulang
                        fetch(`/jadwalRuangan/${data.id}/related`)
                            .then(response => response.json())
                            .then(relatedData => {
                                const isRepeatCheckbox = document.getElementById('edit-isRepeat');
                                const repeatLabel = document.querySelector('label[for="edit-isRepeat"]');

                                if (relatedData.length > 0) {
                                    isRepeatCheckbox.style.display = 'inline';
                                    repeatLabel.innerText = `Apakah Anda ingin mengubah sisa ${relatedData.length} jadwal?`;
                                    isRepeatCheckbox.checked = false;
                                } else {
                                    isRepeatCheckbox.style.display = 'none';
                                    repeatLabel.innerText = '';
                                }
                            })
                            .catch(error => {
                                console.error('Error checking related schedules:', error);
                            });

                        // Perbarui URL form dengan ID jadwal
                        const editForm = document.getElementById('editJadwalForm');
                        editForm.action = `/jadwalRuangan/${data.id}/edit`;

                        // Tampilkan modal edit jadwal
                        const modal = new bootstrap.Modal(document.getElementById('editJadwalModal'));
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching rooms:', error);
                    });
            }


        });

    </script>
@endsection
