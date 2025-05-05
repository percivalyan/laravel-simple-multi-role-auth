@extends('layouts.app')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="card card-dark">
            <div class="card-header">
                <h3 class="card-title">User Activity Logs</h3>
            </div>

            <div class="card-body table-responsive p-0" style="max-height: 500px;">
                <table class="table table-sm table-hover table-striped text-sm text-nowrap align-middle">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>IP</th>
                            <th>User Agent</th>
                            <th>Payload</th>
                            <th>Last Activity</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sessions as $session)
                        <tr>
                            <td>
                                {{ ($sessions->currentPage() - 1) * $sessions->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                {{ $session->user ? $session->user->name . ' (' . $session->user->email . ')' : 'Guest' }}
                            </td>
                            <td>{{ $session->user->role ?? '-' }}</td>
                            <td>
                                <span class="badge badge-primary">{{ strtoupper($session->action) }}</span>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($session->description, 50) }}</td>
                            <td>{{ $session->ip_address }}</td>
                            <td style="max-width: 250px;">
                                <small class="d-block text-wrap">{{ \Illuminate\Support\Str::limit($session->user_agent, 100) }}</small>
                            </td>
                            <td style="max-width: 300px;">
                                <code class="d-block text-wrap">{{ \Illuminate\Support\Str::limit($session->payload, 300) }}</code>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->toDayDateTimeString() }}<br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                                </small>
                            </td>
                            <td>
                                @if (Auth::user()->role === 'admin')
                                <form action="{{ route('session.logs.destroy', $session->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">No session activity found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($sessions->hasPages())
            <div class="card-footer clearfix">
                {{ $sessions->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
