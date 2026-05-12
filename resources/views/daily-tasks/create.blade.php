@extends('layouts.app')
@section('title', 'Assign Tugas Harian')
@section('header', 'Assign Tugas Harian')
@section('subtitle', 'Tugaskan template pekerjaan ke petugas')
@section('content')
<div class="page-header"><div><h2>Assign Tugas Harian</h2><p>Pilih tanggal, petugas, dan template tugas.</p></div></div>
<form method="POST" action="{{ route('daily-tasks.store') }}">@include('daily-tasks._form')</form>
@endsection
