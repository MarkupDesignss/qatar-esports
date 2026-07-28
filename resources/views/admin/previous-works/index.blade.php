@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
            <i class="bi bi-clock-history me-2"></i>Previous Work
        </h4>
        <a href="{{ route('admin.previous-works.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Add New
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Works Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" width="50">Sr.No.</th>
                            <th class="text-center" width="100">Image</th>
                            <th class="text-center">Title</th>
                            <th class="text-center" width="150">Category</th>
                            <th class="text-center" width="130">Date</th>
                            <th class="text-center" width="100">Status</th>
                            <th class="text-center" width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($works as $work)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                
                                <td class="text-center">
                                    <img src="{{ asset('storage/'.$work->image) }}" 
                                         class="rounded" 
                                         style="height:40px; width:60px; object-fit:cover;">
                                </td>
                                
                                <td style="text-align:center">
                                    <strong class="small">{{ $work->title }}</strong>
                                </td>
                                
                                <td>
                                    <span class="small">{{ $work->category }}</span>
                                </td>
                                
                                <td class="text-center small">
                                    {{ $work->event_date ? date('d M Y', strtotime($work->event_date)) : '-' }}
                                </td>
                                
                                <td class="text-center">
                                    <span class="badge {{ $work->status ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                                        {{ $work->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                
                                <td>
                                    <div class="d-flex flex-column flex-sm-row gap-1 justify-content-center align-items-center">
                                        <a href="{{ route('admin.previous-works.edit', $work) }}"
                                           class="btn btn-warning btn-sm w-100 w-sm-auto"
                                           data-bs-toggle="tooltip"
                                           title="Edit Previous Work">
                                            <i class="bi bi-pencil-square"></i>
                                            <span class="d-none d-sm-inline"> Edit</span>
                                        </a>
                                        
                                        <form action="{{ route('admin.previous-works.destroy', $work) }}"
                                              method="POST"
                                              class="d-inline w-100 w-sm-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm w-100 w-sm-auto"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete Previous Work"
                                                    onclick="return confirm('Are you sure you want to delete this work?')">
                                                <i class="bi bi-trash"></i>
                                                <span class="d-none d-sm-inline"> Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-clock-history fs-1 text-muted mb-2"></i>
                                        <p class="mb-0 text-muted">No previous work found</p>
                                        <a href="{{ route('admin.previous-works.create') }}" class="btn btn-primary btn-sm mt-3">
                                            <i class="bi bi-plus-circle me-1"></i> Add First Work
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($works instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="card-footer bg-white py-2 py-sm-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <small class="text-muted order-2 order-sm-1">
                    Showing {{ $works->firstItem() ?? 0 }} to {{ $works->lastItem() ?? 0 }}
                    of {{ $works->total() }} entries
                </small>
                <div class="order-1 order-sm-2 w-100 w-sm-auto">
                    {{ $works->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection