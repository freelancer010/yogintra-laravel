<nav class="main-header navbar navbar-expand navbar-white navbar-light" aria-label="Workspace tools">
    <ul class="navbar-nav"><li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Toggle navigation"><i class="fas fa-bars" aria-hidden="true"></i></a></li></ul>
    <div class="workspace-heading"><span>YOGINTRA</span><strong>Workspace</strong></div>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item d-none d-sm-block"><span class="workspace-date">{{ now()->format('D, d M Y') }}</span></li>
        <li class="nav-item"><a class="nav-link" data-widget="fullscreen" href="#" role="button" aria-label="Toggle fullscreen"><i class="fas fa-expand-arrows-alt" aria-hidden="true"></i></a></li>
        <li class="nav-item"><button type="button" class="nav-link workspace-refresh" onclick="location.reload()" aria-label="Refresh page"><i class="fas fa-sync-alt" aria-hidden="true"></i></button></li>
        <li class="nav-item navbar-user">
            <img src="{{ session('profile_image') ? asset(session('profile_image')) : asset('assets/dist/img/default-profile.png') }}" alt="" class="navbar-user-avatar">
            <div><span class="navbar-user-name">{{ session('fullName', 'Team member') }}</span><small>{{ session('admin_role', 'Team member') }}</small></div>
        </li>
        <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}" aria-label="Sign out"><i class="fas fa-sign-out-alt" aria-hidden="true"></i></a></li>
    </ul>
</nav>
