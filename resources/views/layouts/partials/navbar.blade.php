<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav align-items-center">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ tenant() ? route('dashboard') : url('/super-admin/dashboard') }}" class="nav-link font-weight-bold text-primary"><i class="fas fa-home"></i> Dashboard</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto align-items-center">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      @if(tenant())
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          @if(Auth::user()->unreadNotifications->count() > 0)
            <span class="badge badge-warning navbar-badge">{{ Auth::user()->unreadNotifications->count() }}</span>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right border-0 shadow-lg">
          <span class="dropdown-header font-weight-bold text-sm">{{ Auth::user()->unreadNotifications->count() }} Notifikasi Baru</span>
          <div class="dropdown-divider"></div>
          
          @forelse(Auth::user()->unreadNotifications->take(5) as $notification)
            <a href="{{ route('tenant.notifications.show', [tenant('id'), $notification->id]) }}" class="dropdown-item">
              <i class="{{ $notification->data['icon'] ?? 'fas fa-info-circle' }} mr-2 {{ $notification->data['color'] ?? 'text-primary' }} text-xs"></i>
              <span class="text-xs">{{ Str::limit($notification->data['message'], 35) }}</span>
              <span class="float-right text-muted text-xs">{{ $notification->created_at->diffForHumans() }}</span>
            </a>
            <div class="dropdown-divider"></div>
          @empty
            <div class="p-3 text-center text-muted text-xs">Tidak ada notifikasi baru</div>
          @endforelse
          
          <a href="{{ route('tenant.notifications.index', tenant('id')) }}" class="dropdown-item dropdown-footer text-xs font-weight-bold text-primary">Lihat Semua Notifikasi</a>
        </div>
      </li>
      @endif
      <li class="nav-item dropdown">
        <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" aria-expanded="false">
          <img src="{{ Auth::user()->profile_photo ? global_asset('storage/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF' }}" class="img-circle elevation-1 mr-2" alt="User Image" style="width: 28px; height: 28px; object-fit: cover;">
          <span class="font-weight-bold text-dark text-sm">{{ strtoupper(Auth::user()->name) }} <i class="fas fa-caret-down ml-1"></i></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right border-0 shadow-lg">
          <div class="dropdown-header bg-light">
            <h6 class="text-xs text-uppercase font-weight-bold text-muted mb-1">Akun Administrator</h6>
            <div class="d-flex align-items-center">
                <img src="{{ Auth::user()->profile_photo ? global_asset('storage/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF' }}" class="img-circle border border-light mr-2" style="width: 40px; height: 40px; object-fit: cover;">
                <div>
                    <div class="font-weight-bold text-dark">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-muted">{{ Auth::user()->email }}</div>
                </div>
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <a href="{{ tenant() ? route('profile.edit') : route('super-admin.profile.edit') }}" class="dropdown-item">
            <i class="fas fa-user-cog mr-2 text-primary"></i> Profil Saya
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-power-off mr-2 text-danger"></i> Keluar (Logout)
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
          </form>
        </div>
      </li>
    </ul>
</nav>
