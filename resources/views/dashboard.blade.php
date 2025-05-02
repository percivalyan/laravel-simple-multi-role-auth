@extends('layouts.app')

@section('content')
    <h1>Welcome, {{ Auth::user()->name }}!</h1>
    <p>Role: {{ Auth::user()->role }}</p>
    <a href="/logout">Logout</a>
@endsection
