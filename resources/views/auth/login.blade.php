<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in | YogIntra Workspace</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/site-logo.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/modern.css') }}?v=1">
</head>
<body class="yogintra-login">
    <aside class="login-story">
        <div class="login-story-brand">YOGINTRA</div>
        <div><div class="eyebrow">CONNECTED BY WELLBEING</div><h1>Care for your<br>community.<br><em>Grow together.</em></h1><p>A thoughtful space to manage your people, nurture connections, and keep your practice moving forward.</p></div>
        <div class="login-story-footer">Mindful practice. Meaningful connections.</div>
    </aside>
    <main class="login-panel">
        <div class="login-form-wrap">
            <img class="login-logo" src="{{ asset('assets/img/logo.png') }}" alt="YogIntra">
            <div class="eyebrow">YOUR TEAM WORKSPACE</div>
            <h2>Welcome back.</h2>
            <p>Sign in to take care of what matters today.</p>
            @if(session('error'))<div class="alert alert-danger" role="alert">{{ session('error') }}</div>@endif
            @if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="login-field">
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" class="form-control @error('username') is-invalid @enderror" placeholder="Enter your username" value="{{ old('username') }}" autocomplete="username" required @error('username') aria-describedby="username-error" @enderror>
                    @error('username')<span id="username-error" class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="login-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" autocomplete="current-password" required @error('password') aria-describedby="password-error" @enderror>
                    @error('password')<span id="password-error" class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="btn btn-primary btn-block">Sign in to workspace <span aria-hidden="true">&rarr;</span></button>
            </form>
            <div class="login-note">Need access? Please contact your administrator.</div>
        </div>
    </main>
</body>
</html>
