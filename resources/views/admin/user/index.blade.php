@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <div>
            <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                <i class="bi bi-people me-2"></i>Users Management
            </h4>
            <ol class="breadcrumb mb-0 mt-1 small">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-2 g-sm-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body p-2 p-sm-3">
                    <div class="text-xs fw-bold text-primary text-uppercase mb-1 small">Total Users</div>
                    <div class="h5 fw-bold mb-0">{{ $users->total() }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body p-2 p-sm-3">
                    <div class="text-xs fw-bold text-success text-uppercase mb-1 small">Active Users</div>
                    <div class="h5 fw-bold mb-0">
                        {{ App\Models\User::where('status', 1)->count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body p-2 p-sm-3">
                    <div class="text-xs fw-bold text-warning text-uppercase mb-1 small">Inactive Users</div>
                    <div class="h5 fw-bold mb-0">
                        {{ App\Models\User::where('status', 0)->count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body p-2 p-sm-3">
                    <div class="text-xs fw-bold text-info text-uppercase mb-1 small">New This Month</div>
                    <div class="h5 fw-bold mb-0">
                        {{ App\Models\User::where('created_at', '>=', now()->startOfMonth())->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- User Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-2 py-sm-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <h5 class="mb-0 fw-bold small w-20">User List</h5>
                <div class="d-flex gap-2 w-100 w-sm-auto">
                    <form action="{{ route('admin.user.index') }}" method="GET" class="d-flex gap-2 w-100">
                        <input type="text" 
                               class="form-control form-control-sm" 
                               placeholder="Search by name, username, email, mobile..." 
                               name="search" 
                               value="{{ request('search') }}"
                               id="searchUsers" 
                               style="min-width: 200px; max-width: 300px;">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.user.index') }}" class="btn btn-secondary btn-sm" title="Clear search">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" width="50">#</th>
                            <th>User</th>
                            <th class="d-none d-md-table-cell">Contact</th>
                            <th class="d-table-cell d-md-none">Contact</th>
                            <th class="text-center d-none d-sm-table-cell">Username</th>
                            @if (hasPermission('users.edit'))
                            <th class="text-center d-none d-sm-table-cell">Status</th>
                            @endif
                            <th class="text-end d-none d-lg-table-cell">Joined</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 32px; height: 32px; font-size: 14px; flex-shrink: 0;">
                                            {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong class="small">{{ $user->first_name }} {{ $user->last_name }}</strong>
                                            @if($user->username)
                                                <div class="d-md-none text-muted small">@ {{ $user->username }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="d-none d-md-table-cell">
                                    <div class="small">
                                        @if($user->country_code)
                                            {{ $user->country_code }} 
                                        @endif
                                        {{ $user->mobile }}
                                    </div>
                                    <div class="text-muted small">{{ $user->email }}</div>
                                </td>

                                <td class="d-table-cell d-md-none">
                                    <div class="small">{{ $user->email }}</div>
                                    @if($user->mobile)
                                        <div class="text-muted small">
                                            @if($user->country_code)
                                                {{ $user->country_code }} 
                                            @endif
                                            {{ $user->mobile }}
                                        </div>
                                    @endif
                                </td>

                                <td class="text-center d-none d-sm-table-cell">
                                    <span class="badge bg-info">{{ $user->username ?? 'N/A' }}</span>
                                </td>

                                @if (hasPermission('users.edit'))
                                <td class="text-center d-none d-sm-table-cell">
                                    <form method="POST" action="{{ route('admin.user.toggle-status', $user->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-sm {{ $user->status == 1 ? 'btn-success' : 'btn-danger' }} w-100">
                                            {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                @endif

                                <td class="text-end d-none d-lg-table-cell small">
                                    {{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}
                                </td>

                                <td class="text-center">
                                    <div class="d-flex flex-column flex-sm-row gap-1 justify-content-center align-items-center">
                                        <a href="{{ route('admin.users.view', $user->id) }}"
                                            class="btn btn-sm btn-primary w-100 w-sm-auto"
                                            data-bs-toggle="tooltip"
                                            title="View User">
                                            <i class="bi bi-eye"></i>
                                            <span class="d-none d-sm-inline"> View</span>
                                        </a>
                                    
                                        @if(hasPermission('users.delete'))
                                        <form action="{{ route('admin.users.destroy', $user->id) }}"
                                              method="POST"
                                              class="d-inline delete-user-form w-100 w-sm-auto">
                                            @csrf
                                            @method('DELETE')
                                    
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger w-100 w-sm-auto">
                                                <i class="bi bi-trash"></i>
                                                <span class="d-none d-sm-inline"> Delete</span>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-people fs-1 text-muted mb-2"></i>
                                        <p class="mb-0">No users found</p>
                                        @if(request('search'))
                                            <small class="text-muted mt-2">Try adjusting your search criteria</small>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="card-footer bg-white py-2 py-sm-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <small class="text-muted order-2 order-sm-1">
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }}
                    of {{ $users->total() }} entries
                </small>
                <div class="order-1 order-sm-2 w-100 w-sm-auto">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // Delete user confirmation
    document.querySelectorAll(".delete-user-form").forEach(function(form){
        form.addEventListener("submit", function(e){
            e.preventDefault();

            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this user? This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, Delete",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if(result.isConfirmed){
                    form.submit();
                }
            });
        });
    });

    // Optional: Auto-submit search on Enter key (fallback)
    const searchInput = document.getElementById('searchUsers');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.closest('form').submit();
            }
        });
    }

});
</script>

<style>
.avatar-circle {
    width: 32px;
    height: 32px;
    font-size: 14px;
    flex-shrink: 0;
}
@media (max-width: 576px) {
    .avatar-circle {
        width: 28px;
        height: 28px;
        font-size: 12px;
    }
}

/* Improve table responsiveness */
@media (max-width: 768px) {
    .table-bordered td, 
    .table-bordered th {
        white-space: normal;
        word-break: break-word;
    }
}

/* Button hover effects */
.btn-sm {
    transition: all 0.2s ease;
}
.btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
@endsection