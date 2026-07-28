@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
            <i class="bi bi-envelope me-2"></i>Contact Page Settings
        </h4>
        @if (hasPermission('settings.update'))
        <a href="{{ route('admin.contact-settings.edit') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil me-1"></i> Edit Contact Settings
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

  

    {{-- Settings Cards Grid --}}
    <div class="row g-3 g-sm-4">
        {{-- Get in Touch --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-secondary text-white py-2">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-chat-dots me-2"></i>Get in Touch</h6>
                </div>
                <div class="card-body p-3">
                    <h6 class="fw-bold small text-truncate">{{ $settings->get_in_touch_title ?? 'N/A' }}</h6>
                    <p class="text-muted small">{{ Str::limit($settings->get_in_touch_desc ?? 'N/A', 80) }}</p>
                </div>
            </div>
        </div>

        {{-- Partnership --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-handshake me-2"></i>Partnership</h6>
                </div>
                <div class="card-body p-3">
                    <h6 class="fw-bold small text-truncate">{{ $settings->partnership_title ?? 'N/A' }}</h6>
                    <p class="text-muted small">{{ Str::limit($settings->partnership_description ?? 'N/A', 60) }}</p>
                    <div class="mt-2">
                        <span class="badge bg-light text-dark small"><i class="bi bi-envelope me-1"></i> {{ $settings->partnership_email ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sales --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-success text-white py-2">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-cart me-2"></i>Sales</h6>
                </div>
                <div class="card-body p-3">
                    <h6 class="fw-bold small text-truncate">{{ $settings->sales_title ?? 'N/A' }}</h6>
                    <p class="text-muted small">{{ Str::limit($settings->sales_description ?? 'N/A', 60) }}</p>
                    <div class="mt-2">
                        <span class="badge bg-light text-dark small"><i class="bi bi-envelope me-1"></i> {{ $settings->sales_email ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Technical Support --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-info text-white py-2">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-headset me-2"></i>Technical Support</h6>
                </div>
                <div class="card-body p-3">
                    <h6 class="fw-bold small text-truncate">{{ $settings->technical_title ?? 'N/A' }}</h6>
                    <p class="text-muted small">{{ Str::limit($settings->technical_description ?? 'N/A', 60) }}</p>
                    <div class="mt-2">
                        <span class="badge bg-light text-dark small"><i class="bi bi-envelope me-1"></i> {{ $settings->technical_email ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Background Image --}}
    @if($settings->contact_image)
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white py-2 py-sm-3">
                <h5 class="mb-0 fw-bold small"><i class="bi bi-image me-2"></i>Background Image</h5>
            </div>
            <div class="card-body p-3 p-sm-4">
                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3">
                    <img src="{{ asset('storage/'.$settings->contact_image) }}" 
                         class="img-fluid rounded border"
                         style="max-height: 150px; width: auto; max-width: 100%; object-fit: contain;">
                    <div>
                        <small class="text-muted d-block">File: {{ basename($settings->contact_image) }}</small>
                        <small class="text-muted d-block">Last updated: {{ $settings->updated_at->format('d M Y h:i A') }}</small>
                    </div>
                </div>
            </div>
        </div>
    @endif


</div>
@endsection