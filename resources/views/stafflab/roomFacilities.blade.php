@extends('layouts.starter')

@section('header-content')
    <h3>Pendataan Ruangan</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <h4>Daftar Ruangan</h4>
        <table class="table table-bordered table-striped mt-3">
            <tbody>
                @foreach($ruangan as $room)
                    <tr>
                        <td>{{ $room->nama_ruangan }}</td>

{{--                        <td>{{ $room->kapasitas }}</td>--}}
{{--                        <td>--}}
{{--                            @if($room->fasilitas->isNotEmpty())--}}
{{--                                <ul>--}}
{{--                                    @foreach($room->fasilitas as $fasilitas)--}}
{{--                                        <li>{{ $fasilitas->nama_barang }}</li>--}}
{{--                                    @endforeach--}}
{{--                                </ul>--}}
{{--                            @else--}}
{{--                                <span>Tidak ada fasilitas</span>--}}
{{--                            @endif--}}
{{--                        </td>--}}
                        <td>
                            <center>
                                <a href="{{ route('stafflab.editFacilities', $room->room_id) }}" class="btn btn-sm btn-warning">Lihat Fasilitas</a>
                            </center>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
