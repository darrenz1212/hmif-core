@extends('layouts.starter')

@section('header-content')
@endsection

@section('main-content')
    <div class="container mt-4 d-flex justify-content-center align-items-center" style="height: 40vh;">
        <h1 class="display-1">Selamat Datang di LabCore, {{ Auth::user()->name }}!</h1>
    </div>
@endsection