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
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Previous Work
                </h4>
                <a href="{{ route('admin.previous-works.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-sm-4">
            <form method="POST" enctype="multipart/form-data"
                  action="{{ route('admin.previous-works.update', $previousWork) }}"
                  novalidate>
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 col-lg-8">

                        {{-- Category --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <input class="form-control form-control-sm @error('category') is-invalid @enderror" 
                                   name="category"
                                   value="{{ old('category', $previousWork->category) }}" 
                                   required>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Title --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input class="form-control form-control-sm @error('title') is-invalid @enderror" 
                                   name="title"
                                   value="{{ old('title', $previousWork->title) }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control form-control-sm" 
                                      id="description" 
                                      rows="4"
                                      name="description">{{ old('description', $previousWork->description) }}</textarea>
                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="col-12 col-lg-4">
                        <div class="card border shadow-sm">
                            <div class="card-body p-3 p-sm-4">

                                {{-- Event Date --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Event Date</label>
                                    <input type="date" 
                                           class="form-control form-control-sm @error('event_date') is-invalid @enderror" 
                                           name="event_date"
                                           value="{{ old('event_date', $previousWork->event_date) }}">
                                    @error('event_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Video URL --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Video URL</label>
                                    <input class="form-control form-control-sm @error('video_url') is-invalid @enderror" 
                                           name="video_url"
                                           value="{{ old('video_url', $previousWork->video_url) }}"
                                           placeholder="https://youtube.com/...">
                                    @error('video_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($previousWork->video_url)
                                        <div class="mt-1">
                                            <a href="{{ $previousWork->video_url }}" target="_blank" class="text-decoration-none small">
                                                <i class="bi bi-link-45deg"></i> Current Video
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                {{-- Current Image --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Current Image</label>
                                    <div class="border rounded p-2 bg-light text-center">
                                        <img src="{{ asset('storage/'.$previousWork->image) }}"
                                             class="img-fluid rounded"
                                             style="max-height: 100px; width: auto; object-fit: contain;">
                                    </div>
                                </div>

                                {{-- Change Image --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Change Image</label>
                                    <input type="file" 
                                           class="form-control form-control-sm @error('image') is-invalid @enderror" 
                                           name="image">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Leave empty to keep current image</small>
                                </div>

                                {{-- Status --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select class="form-select form-select-sm" name="status">
                                        <option value="1" {{ old('status', $previousWork->status) ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $previousWork->status) ? '' : 'selected' }}>Inactive</option>
                                    </select>
                                </div>

                                {{-- Preview --}}
                                <div class="mb-3 text-center">
                                    <label class="form-label fw-semibold d-block">New Image Preview</label>
                                    <div class="border rounded p-2 bg-light" id="previewContainer">
                                        <div id="previewPlaceholder" class="text-center text-muted">
                                            <i class="bi bi-image fs-1 d-block mb-2"></i>
                                            <small>New image preview</small>
                                        </div>
                                        <img id="imagePreview" class="img-fluid d-none" style="max-height: 120px; width: 100%; object-fit: cover; border-radius: 4px;">
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-check-circle me-1"></i> Update Work
                                    </button>
                                    <a href="{{ route('admin.previous-works.index') }}" class="btn btn-secondary btn-sm">
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

<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let editorInstance;
        const textarea = document.getElementById('description');

        if (textarea) {
            ClassicEditor.create(textarea, {
                toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'link', 'undo', 'redo']
            }).then(editor => {
                editorInstance = editor;
            }).catch(error => console.error(error));
        }

        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                if (editorInstance) {
                    editorInstance.updateSourceElement();
                }
            });
        }

        // Image Preview
        const fileInput = document.querySelector('input[name="image"]');
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
    });
</script>
@endsection