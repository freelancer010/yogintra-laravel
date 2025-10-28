@extends('layouts.layout')

@section('title', 'Offline')

@section('content')
    <div class="container mt-5 text-center">
        <h1>You are offline</h1>
        <p>It looks like you're not connected to the internet. Some features may not be available.</p>
        <p>
            <a href="{{ url('/') }}" class="btn btn-primary">Go to Home</a>
        </p>
    </div>
@endsection
