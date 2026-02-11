@extends('layouts.backend')

@section('title', 'Dashboard')

@section('content')
    <h3 class="font-weight-bold">Welcome {{ auth()->user()->name ?? 'Admin' }}</h3>
    <p>Login berhasil 🎉</p>
@endsection
