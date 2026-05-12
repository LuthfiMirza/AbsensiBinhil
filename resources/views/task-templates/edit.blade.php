@extends('layouts.app')
@section('title', 'Edit Master Tugas')
@section('header', 'Edit Master Tugas')
@section('subtitle', 'Perbarui template pekerjaan standar')
@section('content')
<div class="page-header"><div><h2>Edit Master Tugas</h2><p>{{ $taskTemplate->name }}</p></div></div>
<form method="POST" action="{{ route('task-templates.update', $taskTemplate) }}">@method('PUT') @include('task-templates._form')</form>
@endsection
