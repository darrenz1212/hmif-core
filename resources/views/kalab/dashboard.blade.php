@extends('layouts.starter')

@section('header-content')
    <h3>Halo Kepala Lab</h3>
@endsection

@section('main-content')
    <div class="container mt-4">
        <h1>Halo, {{ Auth::user()->name }}</h1>

        <!-- Buttons -->
        <a href="{{ route('kalab-showroom') }}" class="btn btn-primary">Room Control</a>
        <a href="{{ route('inventory') }}" class="btn btn-primary mr-2">Inventory Control</a>
        <a href="#" class="btn btn-primary mr-2">Schedule Control</a>
        <a href="#" class="btn btn-primary mr-2">Pengajuan</a>
    </div>
@endsection
