@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
            <i class="bi bi-info-circle me-2"></i>About Us
        </h4>
        @if (hasPermission('about.update'))
        <a href="{{ route('admin.about.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Add Section
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

    {{-- About Card (from abouts table) --}}
    @if($about)
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white py-2 py-sm-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                    <h5 class="mb-0 fw-bold small">Main About Page</h5>
                    @if (hasPermission('about.update'))
                    <a href="{{ route('admin.about.edit-main') }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i> Edit Main
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body p-3 p-sm-4">
                <div class="d-flex flex-column flex-sm-row align-items-start gap-3 gap-sm-4">
                    {{-- Image --}}
                    <div class="flex-shrink-0 text-center">
                        @if($about->image)
                            <img src="{{ asset('storage/'.$about->image) }}" 
                                 class="rounded border" 
                                 style="height:100px; width:120px; object-fit:cover;">
                        @else
                            <div class="bg-light rounded border p-3 text-center" style="height:100px; width:120px; display:flex; align-items:center; justify-content:center;">
                                <span class="text-muted small">No Image</span>
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-1">{{ $about->heading }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit(strip_tags($about->description), 150) }}</p>
                        @if($about->badge)
                            <span class="badge bg-secondary">{{ $about->badge }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            No main About page found. 
            <a href="{{ route('admin.about.edit-main') }}" class="alert-link">Create one now</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ✅ Existing Sections Table (mission, vision, goals) --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-2 py-sm-3">
            <h5 class="mb-0 fw-bold small">About Sections</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" width="50">Sr.No.</th>
                            <th class="text-center" width="100">Image</th>
                            <th class="text-center" width="100">Type</th>
                            <th class="text-center">Title</th>
                            <th class="text-center" width="100">Status</th>
                            @if (hasPermission('about.update'))
                            <th class="text-center" width="150">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($sections as $section)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                
                                <td class="text-center">
                                    @if($section->image)
                                        <img src="{{ asset('storage/'.$section->image) }}" 
                                             class="rounded border" 
                                             style="height:40px; width:60px; object-fit:cover;">
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                
                                <td class="text-center">
                                    <span class="badge bg-info text-capitalize">{{ $section->type }}</span>
                                </td>
                                
                                <td class="text-center">
                                    <strong class="small">{{ $section->title }}</strong>
                                </td>
                                
                                <td class="text-center">
                                    <span class="badge {{ $section->status ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                                        {{ $section->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                
                                @if (hasPermission('about.update'))
                                <td>
                                    <div class="d-flex flex-column flex-sm-row gap-1 justify-content-center align-items-center">
                                        <a href="{{ route('admin.about.edit', $section->id) }}" 
                                           class="btn btn-warning btn-sm w-100 w-sm-auto"
                                           data-bs-toggle="tooltip"
                                           title="Edit Section">
                                            <i class="bi bi-pencil-square"></i>
                                            <span class="d-none d-sm-inline"> Edit</span>
                                        </a>
                                        
                                        <form action="{{ route('admin.about.destroy', $section->id) }}" 
                                              method="POST" 
                                              class="d-inline w-100 w-sm-auto" 
                                              onsubmit="return confirm('Are you sure you want to delete this section?')">
                                            @csrf 
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm w-100 w-sm-auto"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete Section">
                                                <i class="bi bi-trash"></i>
                                                <span class="d-none d-sm-inline"> Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-info-circle fs-1 text-muted mb-2"></i>
                                        <p class="mb-0 text-muted">No sections found</p>
                                        @if (hasPermission('about.update'))
                                        <a href="{{ route('admin.about.create') }}" class="btn btn-primary btn-sm mt-3">
                                            <i class="bi bi-plus-circle me-1"></i> Add First Section
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
    </div>
</div>
@endsection