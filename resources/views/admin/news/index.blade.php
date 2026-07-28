@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
            <i class="bi bi-newspaper me-2"></i>News List
        </h4>
        @if (hasPermission('news.create'))
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Add News
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

    {{-- News Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" width="50">Sr.No.</th>
                            <th class="text-center" width="100">Thumb</th>
                            <th class="text-center">Title</th>
                            <th class="text-center" width="150">Tournament</th>
                            <th class="text-center" width="120">Type</th>
                            <th class="text-center" width="130">Created</th>
                            @if (hasPermission('news.edit') || hasPermission('news.delete'))
                            <th class="text-center" width="150">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($news as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                
                                <td class="text-center">
                                    @if($item->thumbnail)
                                        <img src="{{ asset('storage/'.$item->thumbnail) }}" 
                                             class="rounded" 
                                             style="height:40px; width:60px; object-fit:cover;">
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                
                                <td  class="text-center">  
                                    <strong class="small">{{ $item->title }}</strong>
                                </td>
                                
                                <td>
                                    <span class="small">{{ $item->tournament?->title ?? '-' }}</span>
                                </td>
                                
                                <td>
                                    <span class="badge bg-info">{{ $item->type->name ?? '—' }}</span>
                                </td>
                                
                                <td class="text-center small">
                                    {{ $item->created_at->format('d M Y') }}
                                </td>
                                
                                <td>
                                    <div class="d-flex flex-column flex-sm-row gap-1 justify-content-center align-items-center">
                                        @if (hasPermission('news.edit'))
                                        <a href="{{ route('admin.news.edit', $item->id) }}"
                                           class="btn btn-warning btn-sm w-100 w-sm-auto"
                                           data-bs-toggle="tooltip"
                                           title="Edit News">
                                            <i class="bi bi-pencil-square"></i>
                                            <span class="d-none d-sm-inline"> Edit</span>
                                        </a>
                                        @endif
                                        
                                        @if (hasPermission('news.delete'))
                                        <form action="{{ route('admin.news.destroy', $item->id) }}"
                                              method="POST"
                                              class="d-inline w-100 w-sm-auto"
                                              onsubmit="return confirm('Are you sure you want to delete this news?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm w-100 w-sm-auto"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete News">
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
                                <td colspan="7" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-newspaper fs-1 text-muted mb-2"></i>
                                        <p class="mb-0 text-muted">No news found</p>
                                        @if (hasPermission('news.create'))
                                        <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-sm mt-3">
                                            <i class="bi bi-plus-circle me-1"></i> Add First News
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
        @if($news instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="card-footer bg-white py-2 py-sm-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <small class="text-muted order-2 order-sm-1">
                    Showing {{ $news->firstItem() ?? 0 }} to {{ $news->lastItem() ?? 0 }}
                    of {{ $news->total() }} entries
                </small>
                <div class="order-1 order-sm-2 w-100 w-sm-auto">
                    {{ $news->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection