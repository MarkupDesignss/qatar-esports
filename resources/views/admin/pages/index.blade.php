@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
            <i class="bi bi-file-earmark-text me-2"></i>Legal Pages
        </h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    

    {{-- Legal Pages Cards --}}
    <div class="row g-3 g-sm-4">
        {{-- Privacy Policy --}}
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary text-white py-2">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-shield-lock me-2"></i>
                        <h6 class="mb-0 fw-bold">Privacy Policy</h6>
                    </div>
                </div>
                <div class="card-body p-3">
                    <h6 class="fw-bold small text-truncate">{{ $privacy->title ?? 'N/A' }}</h6>
                    <p class="text-muted small">{{ Str::limit(strip_tags($privacy->content ?? ''), 120) }}</p>
                    <div class="mt-2">
                        <span class="badge bg-light text-dark small"><i class="bi bi-link-45deg me-1"></i> {{ $privacy->slug ?? 'N/A' }}</span>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Updated: {{ $privacy->updated_at->format('d M Y') ?? 'N/A' }}</small>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 py-2">
                    @if (hasPermission('legal.update'))
                    <a href="{{ route('admin.pages.edit', $privacy->slug) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Terms of Service --}}
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-success text-white py-2">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        <h6 class="mb-0 fw-bold">Terms of Service</h6>
                    </div>
                </div>
                <div class="card-body p-3">
                    <h6 class="fw-bold small text-truncate">{{ $terms->title ?? 'N/A' }}</h6>
                    <p class="text-muted small">{{ Str::limit(strip_tags($terms->content ?? ''), 120) }}</p>
                    <div class="mt-2">
                        <span class="badge bg-light text-dark small"><i class="bi bi-link-45deg me-1"></i> {{ $terms->slug ?? 'N/A' }}</span>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Updated: {{ $terms->updated_at->format('d M Y') ?? 'N/A' }}</small>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 py-2">
                    @if (hasPermission('legal.update'))
                    <a href="{{ route('admin.pages.edit', $terms->slug) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Cookie Policy --}}
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-info text-white py-2">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-cookie me-2"></i>
                        <h6 class="mb-0 fw-bold">Cookie Policy</h6>
                    </div>
                </div>
                <div class="card-body p-3">
                    <h6 class="fw-bold small text-truncate">{{ $cookie->title ?? 'N/A' }}</h6>
                    <p class="text-muted small">{{ Str::limit(strip_tags($cookie->content ?? ''), 120) }}</p>
                    <div class="mt-2">
                        <span class="badge bg-light text-dark small"><i class="bi bi-link-45deg me-1"></i> {{ $cookie->slug ?? 'N/A' }}</span>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Updated: {{ $cookie->updated_at->format('d M Y') ?? 'N/A' }}</small>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 py-2">
                    @if (hasPermission('legal.update'))
                    <a href="{{ route('admin.pages.edit', $cookie->slug) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>


</div>
@endsection