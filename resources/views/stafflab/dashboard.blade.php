@extends('layouts.starter')

@section('header-content')
    <h3>Halo Staff Lab</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <h1>Halo, {{ Auth::user()->name }}</h1>

        <!-- Buttons -->
        <a href="{{ route('stafflab.roomFacilities') }}" class="btn btn-primary">Data Fasilitas Ruangan</a>
        <a href="{{ route('stafflab.rooms') }}" class="btn btn-primary mr-2">Data Ruangan</a>
        <a href="{{ route('stafflab.inventory') }}" class="btn btn-primary mr-2">Data Barang</a>
    </div>
@endsection