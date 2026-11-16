@extends('layouts.layout')
@section('title', 'Change password')
@section('content')
<div class="content-wrapper">
    <section class="content-header"><div class="container-fluid"><h1>Change password</h1></div></section>
    <section class="content"><div class="container-fluid">
        <div class="row"><div class="col-lg-6 col-md-8">
            <div class="card">
                <div class="card-header"><h2 class="card-title">Super admin account</h2></div>
                <div class="card-body">
                    <p class="text-muted">Enter your current password and choose a new password with at least 8 characters.</p>
                    @if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
                    <form action="{{ route('account.password.update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="current_password">Current password</label>
                            <input id="current_password" name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password" required maxlength="72" @error('current_password') aria-invalid="true" aria-describedby="current-password-error" @enderror>
                            @error('current_password')<div id="current-password-error" class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="password">New password</label>
                            <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" minlength="8" maxlength="72" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                            @error('password')<div id="password-error" class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm new password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" minlength="8" maxlength="72" required>
                        </div>
                        <div class="d-flex flex-wrap" style="gap:12px">
                            <button type="submit" class="btn btn-primary">Update password</button>
                            <a href="{{ url('/dashboard') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div></div>
    </div></section>
</div>
@endsection
