<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="index3.html" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search"
                            aria-label="Search" />
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Messages Dropdown Menu -->
        <!-- Log Aktivitas (untuk admin & user) -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" title="Recent Activity">
                <i class="fas fa-history"></i>
                @if (count($navbar_logs))
                    <span class="badge badge-info navbar-badge">{{ count($navbar_logs) }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-2">
                <span class="dropdown-item dropdown-header text-sm">
                    {{ count($navbar_logs) }} Recent Activities
                </span>
                <div class="dropdown-divider my-1"></div>
                @forelse ($navbar_logs as $log)
                    <a href="#" class="dropdown-item py-1 px-2 text-sm">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-user-clock mr-2 mt-1 text-primary"></i>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $log->user->name ?? 'Guest' }}</strong>
                                    <span class="badge badge-primary badge-sm">{{ strtoupper($log->action) }}</span>
                                </div>
                                <div class="text-muted small">
                                    {{ \Illuminate\Support\Str::limit($log->description, 40) }}
                                </div>
                                <div class="text-muted small">
                                    {{ \Carbon\Carbon::createFromTimestamp($log->last_activity)->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-divider my-1"></div>
                @empty
                    <span class="dropdown-item text-muted text-sm">No activity found</span>
                @endforelse
                <a href="{{ route('session.logs') }}" class="dropdown-item dropdown-footer text-sm">
                    View All Logs
                </a>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
