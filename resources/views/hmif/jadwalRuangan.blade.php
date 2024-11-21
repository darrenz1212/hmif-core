@extends('layouts.starter')

@section('header-content')
    <h3>Jadwal Ruangan</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <h4>Daftar Jadwal Ruangan</h4>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>ID Jadwal</th>
                    <th>Nama Ruangan</th>
                    <th>Tanggal</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwalRuangan as $jadwal)
                    <tr>
                        <td>{{ $jadwal->id }}</td>
                        <td>{{ $jadwal->nama_ruangan }}</td> <!-- Mengambil nama ruangan melalui relasi -->
                        <td>{{ $jadwal->tanggal }}</td>
                        <td>{{ $jadwal->jam_mulai }}</td>
                        <td>{{ $jadwal->jam_selesai }}</td>
                        <td>{{ $jadwal->keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
