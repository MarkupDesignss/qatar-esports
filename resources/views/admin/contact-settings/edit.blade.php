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
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Contact Settings
                </h4>
                <a href="{{ route('admin.contact-settings.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-sm-4">
            <form action="{{ route('admin.contact-settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Get in Touch --}}
                    <div class="col-12 col-md-6">
                        <div class="card border shadow-sm h-100">
                            <div class="card-header bg-secondary text-white py-2">
                                <h6 class="mb-0 fw-bold"><i class="bi bi-chat-dots me-2"></i>Get in Touch</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Title</label>
                                    <input type="text" 
                                           name="get_in_touch_title" 
                                           class="form-control form-control-sm @error('get_in_touch_title') is-invalid @enderror" 
                                           value="{{ old('get_in_touch_title', $settings->get_in_touch_title) }}">
                                    @error('get_in_touch_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Description</label>
                                    <textarea name="get_in_touch_desc" 
                                              class="form-control form-control-sm @error('get_in_touch_desc') is-invalid @enderror" 
                                              rows="3">{{ old('get_in_touch_desc', $settings->get_in_touch_desc) }}</textarea>
                                    @error('get_in_touch_desc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Partnership --}}
                    <div class="col-12 col-md-6">
                        <div class="card border shadow-sm h-100">
                            <div class="card-header bg-primary text-white py-2">
                                <h6 class="mb-0 fw-bold"><i class="bi bi-handshake me-2"></i>Partnership</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Title</label>
                                    <input type="text" 
                                           name="partnership_title" 
                                           class="form-control form-control-sm @error('partnership_title') is-invalid @enderror" 
                                           value="{{ old('partnership_title', $settings->partnership_title) }}">
                                    @error('partnership_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Description</label>
                                    <textarea name="partnership_description" 
                                              class="form-control form-control-sm @error('partnership_description') is-invalid @enderror" 
                                              rows="3">{{ old('partnership_description', $settings->partnership_description) }}</textarea>
                                    @error('partnership_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Email</label>
                                    <input type="email" 
                                           name="partnership_email" 
                                           class="form-control form-control-sm @error('partnership_email') is-invalid @enderror" 
                                           value="{{ old('partnership_email', $settings->partnership_email) }}">
                                    @error('partnership_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sales --}}
                    <div class="col-12 col-md-6">
                        <div class="card border shadow-sm h-100">
                            <div class="card-header bg-success text-white py-2">
                                <h6 class="mb-0 fw-bold"><i class="bi bi-cart me-2"></i>Sales</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Title</label>
                                    <input type="text" 
                                           name="sales_title" 
                                           class="form-control form-control-sm @error('sales_title') is-invalid @enderror" 
                                           value="{{ old('sales_title', $settings->sales_title) }}">
                                    @error('sales_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Description</label>
                                    <textarea name="sales_description" 
                                              class="form-control form-control-sm @error('sales_description') is-invalid @enderror" 
                                              rows="3">{{ old('sales_description', $settings->sales_description) }}</textarea>
                                    @error('sales_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Email</label>
                                    <input type="email" 
                                           name="sales_email" 
                                           class="form-control form-control-sm @error('sales_email') is-invalid @enderror" 
                                           value="{{ old('sales_email', $settings->sales_email) }}">
                                    @error('sales_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Technical Support --}}
                    <div class="col-12 col-md-6">
                        <div class="card border shadow-sm h-100">
                            <div class="card-header bg-info text-white py-2">
                                <h6 class="mb-0 fw-bold"><i class="bi bi-headset me-2"></i>Technical Support</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Title</label>
                                    <input type="text" 
                                           name="technical_title" 
                                           class="form-control form-control-sm @error('technical_title') is-invalid @enderror" 
                                           value="{{ old('technical_title', $settings->technical_title) }}">
                                    @error('technical_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Description</label>
                                    <textarea name="technical_description" 
                                              class="form-control form-control-sm @error('technical_description') is-invalid @enderror" 
                                              rows="3">{{ old('technical_description', $settings->technical_description) }}</textarea>
                                    @error('technical_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Email</label>
                                    <input type="email" 
                                           name="technical_email" 
                                           class="form-control form-control-sm @error('technical_email') is-invalid @enderror" 
                                           value="{{ old('technical_email', $settings->technical_email) }}">
                                    @error('technical_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Background Image Section --}}
                <div class="card border shadow-sm mt-4">
                    <div class="card-header bg-white py-2 py-sm-3">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-image me-2"></i>Background Image</h6>
                    </div>
                    <div class="card-body p-3 p-sm-4">
                        <div class="row g-3 align-items-center">
                            <div class="col-12 col-sm-6">
                                @if($settings->contact_image)
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold small">Current Image</label>
                                        <div class="border rounded p-2 bg-light">
                                            <img src="{{ asset('storage/'.$settings->contact_image) }}" 
                                                 class="img-fluid rounded"
                                                 style="max-height: 120px; width: auto; max-width: 100%; object-fit: contain;">
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-12 col-sm-6">
                                <div>
                                    <label class="form-label fw-semibold small">Change Image</label>
                                    <input type="file" 
                                           name="contact_image" 
                                           class="form-control form-control-sm @error('contact_image') is-invalid @enderror">
                                    @error('contact_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <small class="text-muted d-block mt-1">Leave empty to keep current image. Allowed: jpg, png, gif, svg, webp</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="mt-4 d-flex flex-column flex-sm-row gap-2 gap-sm-3">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check-circle me-1"></i> Update Settings
                    </button>
                    <a href="{{ route('admin.contact-settings.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image Preview
        const fileInput = document.querySelector('input[name="contact_image"]');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');

        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const fileURL = URL.createObjectURL(file);
                    // Find or create preview
                    let preview = document.querySelector('#imagePreview');
                    if (!preview) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'mt-2';
                        previewDiv.innerHTML = `
                            <label class="form-label fw-semibold small">New Image Preview:</label>
                            <div class="border rounded p-2 bg-light">
                                <img id="imagePreview" class="img-fluid rounded" style="max-height: 120px; width: auto; max-width: 100%; object-fit: contain;">
                            </div>
                        `;
                        fileInput.parentNode.appendChild(previewDiv);
                        preview = document.getElementById('imagePreview');
                    }
                    preview.src = fileURL;
                }
            });
        }
    });
</script>
@endsection