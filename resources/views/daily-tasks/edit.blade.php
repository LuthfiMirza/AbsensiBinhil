@extends('layouts.app')
@section('title', 'Edit Tugas Harian')
@section('header', 'Edit Tugas Harian')
@section('subtitle', 'Perbarui assignment dan status tugas')
@section('content')
<div class="page-header"><div><h2>Edit Tugas Harian</h2><p>{{ $dailyTask->title }}</p></div></div>
<form method="POST" action="{{ route('daily-tasks.update', $dailyTask) }}">@method('PUT') @include('daily-tasks._form')</form>
@endsection
