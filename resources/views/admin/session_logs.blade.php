@extends('layouts.app')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="card card-dark">
            <div class="card-header">
                <h3 class="card-title">User Activity Logs</h3>
            </div>

            <div class="card-body table-responsive p-0" style="max-height: 500px;">
                <table class="table table-head-fixed text-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
                            <th>Payload</th>
                            <th>Last Activity</th>
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
                            <td>{{ $session->description }}</td>
                            <td>{{ $session->ip_address }}</td>
                            <td>
                                {{ \Illuminate\Support\Str::limit($session->user_agent, 200) }}
                            </td>
                            <td>
                                <code style="white-space: pre-wrap;">{{ \Illuminate\Support\Str::limit($session->payload, 500) }}</code>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->toDayDateTimeString() }}
                                <br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                                </small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No session activity found.</td>
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
