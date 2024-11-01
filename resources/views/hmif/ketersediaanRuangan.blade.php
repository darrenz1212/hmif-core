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
                    <th>ID Ruangan</th>
                    <th>Nama Ruangan</th>
                    <th>Kapasitas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($availableRooms as $room)
                    <tr>
                        <td>{{ $room->room_id }}</td>
                        <td>{{ $room->nama_ruangan }}</td>
                        <td>{{ $room->kapasitas }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
