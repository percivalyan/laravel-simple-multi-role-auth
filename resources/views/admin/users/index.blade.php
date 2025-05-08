@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <!-- Card for User Management -->
            <div class="card card-dark">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">User Management</h3>
                </div>

                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#createUserModal">
                    <i class="fas fa-plus-circle"></i> Create User
                </button>

                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover table-striped text-sm text-nowrap align-middle">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 80px;">No</th>
                                <th style="width: 200px;">Name</th>
                                <th style="width: 300px;">Email</th>
                                <th style="width: 120px;">Role</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $index }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : 'info' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#editUserModal{{ $user->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit User -->
                                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit
                                                        User</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="{{ $user->id }}">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" name="name" class="form-control"
                                                            value="{{ old('name', $user->name) }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control"
                                                            value="{{ old('email', $user->email) }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Role</label>
                                                        <select name="role" class="form-control" required>
                                                            @foreach (['admin', 'editor', 'user'] as $role)
                                                                <option value="{{ $role }}"
                                                                    {{ old('role', $user->role) === $role ? 'selected' : '' }}>
                                                                    {{ ucfirst($role) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Password <small>(Leave empty if not changing)</small></label>
                                                        <input type="password" name="password" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-dark">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($users->hasPages())
                    <div class="card-footer clearfix">
                        {{ $users->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Modal Create User -->
    <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createUserModalLabel">Create User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="admin">Admin</option>
                                <option value="editor">Editor</option>
                                <option value="user" selected>User</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Show modal for deletion with the corresponding user id
        $('#deleteUserModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var userId = button.data('id');
            var action = $(this).find('form').attr('action').replace(':user_id', userId);
            $(this).find('form').attr('action', action);
        });
    </script>
@endpush
