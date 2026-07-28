@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
            <i class="bi bi-tags me-2"></i>News Types
        </h4>
        @if (hasPermission('news.create'))
        <a href="{{ route('admin.news-types.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Add Type
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- News Types Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" width="50">Sr.No.</th>
                            <th class="text-center">Name</th>
                            <th class="text-center" width="100">Status</th>
                            <th class="text-center" width="120">Sort Order</th>
                            @if (hasPermission('news.edit') || hasPermission('news.delete'))
                            <th class="text-center" width="150">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($types as $type)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                
                                <td style="text-align:center">
                                    <strong class="small">{{ $type->name }}</strong>
                                </td>
                                
                                <td class="text-center">
                                    <span class="badge {{ $type->is_active ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                                        {{ $type->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $type->sort_order }}</span>
                                </td>
                                
                                <td>
                                    <div class="d-flex flex-column flex-sm-row gap-1 justify-content-center align-items-center">
                                        @if (hasPermission('news.edit'))
                                        <a href="{{ route('admin.news-types.edit', $type) }}" 
                                           class="btn btn-warning btn-sm w-100 w-sm-auto"
                                           data-bs-toggle="tooltip"
                                           title="Edit News Type">
                                            <i class="bi bi-pencil-square"></i>
                                            <span class="d-none d-sm-inline"> Edit</span>
                                        </a>
                                        @endif
                                        
                                        @if (hasPermission('news.delete'))
                                        <form action="{{ route('admin.news-types.destroy', $type) }}" 
                                              method="POST" 
                                              class="d-inline w-100 w-sm-auto">
                                            @csrf 
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm w-100 w-sm-auto"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete News Type"
                                                    onclick="return confirm('Are you sure you want to delete this news type?')">
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
                                <td colspan="5" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-tags fs-1 text-muted mb-2"></i>
                                        <p class="mb-0 text-muted">No news types found</p>
                                        @if (hasPermission('news.create'))
                                        <a href="{{ route('admin.news-types.create') }}" class="btn btn-primary btn-sm mt-3">
                                            <i class="bi bi-plus-circle me-1"></i> Add First Type
                                        </a>
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
        @if($types instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="card-footer bg-white py-2 py-sm-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <small class="text-muted order-2 order-sm-1">
                    Showing {{ $types->firstItem() ?? 0 }} to {{ $types->lastItem() ?? 0 }}
                    of {{ $types->total() }} entries
                </small>
                <div class="order-1 order-sm-2 w-100 w-sm-auto">
                    {{ $types->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection