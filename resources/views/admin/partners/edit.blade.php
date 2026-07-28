@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2 py-sm-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Partner
                </h4>
                <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary btn-sm w-sm-auto">
                    <i class="bi bi-arrow-left me-1"></i> Back to Partners
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-sm-4">
            <form action="{{ route('admin.partners.update', $partner) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 col-lg-8">

                        {{-- Partner Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Partner Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $partner->name) }}"
                                   placeholder="Enter partner name"
                                   required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Current Logo --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Current Logo</label>
                            <div class="border rounded p-3 bg-light">
                                <img src="{{ asset('storage/'.$partner->logo) }}" 
                                     class="img-fluid" 
                                     style="max-height: 80px; width: auto; object-fit: contain;">
                                <div class="mt-2">
                                    <small class="text-muted">File: {{ basename($partner->logo) }}</small>
                                </div>
                            </div>
                        </div>

                        {{-- Change Logo --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Change Logo</label>
                            <input type="file" 
                                   name="logo" 
                                   class="form-control @error('logo') is-invalid @enderror" 
                                   accept="image/*">
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-info-circle me-1"></i>
                                Leave empty to keep current logo. Supported: PNG, JPG, JPEG, SVG, WEBP
                            </small>
                        </div>

                        {{-- Sort Order --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <input type="number" 
                                   name="sort_order" 
                                   class="form-control form-control-lg @error('sort_order') is-invalid @enderror" 
                                   value="{{ old('sort_order', $partner->sort_order) }}"
                                   placeholder="Enter sort order (0 = highest)">
                            @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">Lower numbers appear first</small>
                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="col-12 col-lg-4">
                        <div class="card border shadow-sm">
                            <div class="card-body p-3 p-sm-4">
                                {{-- Status --}}
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="status" 
                                               value="1" 
                                               id="statusSwitch"
                                               {{ old('status', $partner->status) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="statusSwitch">
                                            <span id="statusLabel" class="badge {{ old('status', $partner->status) ? 'bg-success' : 'bg-danger' }}">
                                                {{ old('status', $partner->status) ? 'Active' : 'Inactive' }}
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Preview --}}
                                <div class="mb-4 text-center">
                                    <label class="form-label fw-semibold d-block">New Logo Preview</label>
                                    <div class="border rounded p-3 bg-light" id="previewContainer">
                                        <div id="previewPlaceholder" class="text-center text-muted">
                                            <i class="bi bi-image fs-1 d-block mb-2"></i>
                                            <small>New logo preview</small>
                                        </div>
                                        <img id="imagePreview" class="img-fluid d-none" style="max-height: 120px; width: auto; object-fit: contain;">
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-check-circle me-1"></i> Update Partner
                                    </button>
                                    <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- JavaScript for Live Preview & Status Toggle --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logo Preview
        const fileInput = document.querySelector('input[name="logo"]');
        const previewPlaceholder = document.getElementById('previewPlaceholder');
        const imagePreview = document.getElementById('imagePreview');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileURL = URL.createObjectURL(file);
                imagePreview.src = fileURL;
                imagePreview.classList.remove('d-none');
                previewPlaceholder.classList.add('d-none');
            } else {
                imagePreview.classList.add('d-none');
                previewPlaceholder.classList.remove('d-none');
            }
        });

        // Status Toggle
        const statusSwitch = document.getElementById('statusSwitch');
        const statusLabel = document.getElementById('statusLabel');

        statusSwitch.addEventListener('change', function() {
            if (this.checked) {
                statusLabel.textContent = 'Active';
                statusLabel.className = 'badge bg-success';
            } else {
                statusLabel.textContent = 'Inactive';
                statusLabel.className = 'badge bg-danger';
            }
        });
    });
</script>
@endsection