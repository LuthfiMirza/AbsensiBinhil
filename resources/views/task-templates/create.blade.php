@extends('layouts.app')
@section('title', 'Tambah Master Tugas')
@section('header', 'Tambah Master Tugas')
@section('subtitle', 'Buat template pekerjaan standar')
@section('content')
<div class="page-header"><div><h2>Tambah Master Tugas</h2><p>Template ini nanti dipakai saat assign tugas harian.</p></div></div>
<form method="POST" action="{{ route('task-templates.store') }}">@include('task-templates._form')</form>
@endsection
