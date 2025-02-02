@extends('layouts.starter')

@section('header-content')
    <h3>Pendataan Ruangan</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <h4>Daftar Ruangan</h4>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>Nama Ruangan</th>
                    <th>Kapasitas</th>
                    <th>Fasilitas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ruangan as $room)
                    <tr>
                        <td>{{ $room->nama_ruangan }}</td>
                        <td>{{ $room->kapasitas }}</td>
                        <td>
                            @if($room->fasilitas->isNotEmpty())
                                <ul>
                                    @foreach($room->fasilitas as $fasilitas)
                                        <li>{{ $fasilitas->nama_barang }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span>Tidak ada fasilitas</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
