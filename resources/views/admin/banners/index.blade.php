@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Something went wrong:</strong>
            <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
            <i class="bi bi-images me-2"></i>Banners
        </h4>
        @if (hasPermission('banner.create'))
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm w-sm-auto">
            <i class="bi bi-plus-circle me-1"></i> Add Banner
        </a>
        @endif
    </div>


    {{-- Banners Table --}}
    <div class="card shadow-sm">
        

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="d-none d-sm-table-cell" width="120">Media</th>
                            <th class="d-table-cell d-sm-none" width="80">Media</th>
                            <th>Heading</th>
                            <th class="d-none d-md-table-cell">Description</th>
                            @if (hasPermission('banner.edit') || hasPermission('banner.delete'))
                            <th class="text-center" width="150">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banners as $banner)
                            <tr>
                                <td class="text-center">
                                    @php
                                        $extension = strtolower(pathinfo($banner->image, PATHINFO_EXTENSION));
                                        $videoExtensions = ['mp4', 'webm', 'ogg', 'mov', 'avi'];
                                    @endphp
                                
                                    @if(in_array($extension, $videoExtensions))
                                        <video width="80" height="50" controls preload="metadata" class="rounded">
                                            <source src="{{ asset('storage/' . $banner->image) }}" type="video/{{ $extension }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @else
                                        <img src="{{ asset('storage/' . $banner->image) }}"
                                             alt="Banner"
                                             class="rounded"
                                             style="height:50px; width:80px; object-fit:cover;">
                                    @endif
                                </td>
                                <td>
                                    <strong class="small">{{ $banner->heading }}</strong>
                                    <div class="d-md-none text-muted small mt-1">
                                        {{ Str::limit($banner->description, 30) }}
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell small">
                                    {{ Str::limit($banner->description, 50) }}
                                </td>
                                <td>
                                    <div class="d-flex flex-column flex-sm-row gap-1 justify-content-center align-items-center">
                                        {{-- Edit --}}
                                        @if (hasPermission('banner.edit'))
                                        <a href="{{ route('admin.banners.edit', $banner) }}"
                                           class="btn btn-primary btn-sm w-100 w-sm-auto"
                                           data-bs-toggle="tooltip"
                                           title="Edit Banner">
                                            <i class="bi bi-pencil-square"></i>
                                            <span class="d-none d-sm-inline"> Edit</span>
                                        </a>
                                        @endif
                                        
                                        {{-- Delete --}}
                                        @if (hasPermission('banner.delete'))
                                        <form action="{{ route('admin.banners.destroy', $banner) }}"
                                              method="POST"
                                              class="d-inline w-100 w-sm-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm w-100 w-sm-auto"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete Banner"
                                                    onclick="return confirm('Delete this banner?')">
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
                                        <i class="bi bi-images fs-1 text-muted mb-2"></i>
                                        <p class="mb-0 text-muted">No banners found</p>
                                        @if(hasPermission('banner.create'))
                                        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm mt-3">
                                            <i class="bi bi-plus-circle me-1"></i> Add First Banner
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
        @if($banners instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="card-footer bg-white py-2 py-sm-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <small class="text-muted order-2 order-sm-1">
                    Showing {{ $banners->firstItem() ?? 0 }} to {{ $banners->lastItem() ?? 0 }}
                    of {{ $banners->total() }} entries
                </small>
                <div class="order-1 order-sm-2 w-100 w-sm-auto">
                    {{ $banners->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection