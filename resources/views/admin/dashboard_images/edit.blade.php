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
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Dashboard Images
                </h4>
                <a href="{{ route('admin.dashboard-images.index') }}" class="btn btn-secondary btn-sm w-sm-auto">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-sm-4">
            <form action="{{ route('admin.dashboard-images.update', $dashboardImage->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 col-lg-8">

                        {{-- Image 1 --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Image 1</label>
                            <input type="file" 
                                   name="image1" 
                                   class="form-control @error('image1') is-invalid @enderror" 
                                   accept="image/*">
                            @error('image1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            @if ($dashboardImage->image1)
                                <div class="mt-2">
                                    <label class="form-label small text-muted">Current Image 1:</label>
                                    <div class="border rounded p-2 bg-light">
                                        <img src="{{ asset('storage/' . $dashboardImage->image1) }}" 
                                             class="img-fluid rounded"
                                             style="max-height: 100px; width: auto; object-fit: contain;">
                                      
                                    </div>
                                </div>
                            @endif
                            <small class="text-muted d-block mt-1">Leave empty to keep current image</small>
                        </div>

                        {{-- Image 2 --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Image 2</label>
                            <input type="file" 
                                   name="image2" 
                                   class="form-control @error('image2') is-invalid @enderror" 
                                   accept="image/*">
                            @error('image2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            @if ($dashboardImage->image2)
                                <div class="mt-2">
                                    <label class="form-label small text-muted">Current Image 2:</label>
                                    <div class="border rounded p-2 bg-light">
                                        <img src="{{ asset('storage/' . $dashboardImage->image2) }}" 
                                             class="img-fluid rounded"
                                             style="max-height: 100px; width: auto; object-fit: contain;">
                                       
                                    </div>
                                </div>
                            @endif
                            <small class="text-muted d-block mt-1">Leave empty to keep current image</small>
                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="col-12 col-lg-4">
                        <div class="card border shadow-sm">
                            <div class="card-body p-3 p-sm-4">
                                {{-- Preview Section --}}
                                <div class="mb-4">
                                    <label class="form-label fw-semibold d-block">New Image Preview</label>
                                    <div class="border rounded p-3 bg-light" id="previewContainer1">
                                        <div id="previewPlaceholder1" class="text-center text-muted">
                                            <i class="bi bi-image fs-1 d-block mb-2"></i>
                                            <small>New Image 1 preview</small>
                                        </div>
                                        <img id="imagePreview1" class="img-fluid d-none" style="max-height: 100px; width: 100%; object-fit: cover; border-radius: 4px;">
                                    </div>
                                    <div class="border rounded p-3 bg-light mt-2" id="previewContainer2">
                                        <div id="previewPlaceholder2" class="text-center text-muted">
                                            <i class="bi bi-image fs-1 d-block mb-2"></i>
                                            <small>New Image 2 preview</small>
                                        </div>
                                        <img id="imagePreview2" class="img-fluid d-none" style="max-height: 100px; width: 100%; object-fit: cover; border-radius: 4px;">
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-check-circle me-1"></i> Update Images
                                    </button>
                                    <a href="{{ route('admin.dashboard-images.index') }}" class="btn btn-outline-secondary">
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

{{-- JavaScript for Live Preview --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image 1 Preview
        const imageInput1 = document.querySelector('input[name="image1"]');
        const previewPlaceholder1 = document.getElementById('previewPlaceholder1');
        const imagePreview1 = document.getElementById('imagePreview1');

        imageInput1.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileURL = URL.createObjectURL(file);
                imagePreview1.src = fileURL;
                imagePreview1.classList.remove('d-none');
                previewPlaceholder1.classList.add('d-none');
            } else {
                imagePreview1.classList.add('d-none');
                previewPlaceholder1.classList.remove('d-none');
            }
        });

        // Image 2 Preview
        const imageInput2 = document.querySelector('input[name="image2"]');
        const previewPlaceholder2 = document.getElementById('previewPlaceholder2');
        const imagePreview2 = document.getElementById('imagePreview2');

        imageInput2.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileURL = URL.createObjectURL(file);
                imagePreview2.src = fileURL;
                imagePreview2.classList.remove('d-none');
                previewPlaceholder2.classList.add('d-none');
            } else {
                imagePreview2.classList.add('d-none');
                previewPlaceholder2.classList.remove('d-none');
            }
        });
    });
</script>
@endsection