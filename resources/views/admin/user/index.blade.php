@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="h3 fw-bold text-gray-800 mb-2">Users Management</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Users</div>
                    <div class="h5 fw-bold">{{ $users->total() }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-xs fw-bold text-success text-uppercase mb-1">Active Users</div>
                    <div class="h5 fw-bold">
                        {{ $users->where('status', 1)->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- User Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">User List</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th class="text-end">Joined</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <strong>{{ $user->full_name ?? $user->first_name }}</strong>
                                </td>

                                <td>
                                    {{ $user->mobile }} <br>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </td>

                                <td>
                                    <form method="POST" action="{{ route('admin.user.toggle-status', $user->id) }}">
                                        @csrf
                                        <button class="btn btn-sm {{ $user->status ? 'btn-success' : 'btn-danger' }}">
                                            {{ $user->status ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>

                                <td class="text-end">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>

                               <td class="text-end">
                                    <a href="{{ route('admin.users.view', $user->id) }}"
                                       class="btn btn-sm btn-primary"
                                       data-bs-toggle="tooltip"
                                       title="View User">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    No users found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }}
                    of {{ $users->total() }} entries
                </small>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
