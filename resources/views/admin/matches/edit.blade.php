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
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Match Highlight
                </h4>
                <a href="{{ route('admin.matches.index') }}" class="btn btn-secondary btn-sm w-sm-auto">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-sm-4">
            <form action="{{ route('admin.matches.update', $match) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  novalidate>
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 col-lg-8">

                        {{-- Title --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="title" 
                                   value="{{ old('title', $match->title) }}" 
                                   class="form-control form-control-lg @error('title') is-invalid @enderror"
                                   placeholder="Enter match title"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      class="form-control rich-editor" 
                                      rows="3"
                                      placeholder="Enter short description">{{ old('description', $match->description) }}</textarea>
                        </div>

                        {{-- Video Title --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Video Title</label>
                            <input type="text" 
                                   name="video_title" 
                                   value="{{ old('video_title', $match->video_title) }}" 
                                   class="form-control @error('video_title') is-invalid @enderror"
                                   placeholder="Enter video title">
                            @error('video_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Video URL --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Video URL</label>
                            <input type="url" 
                                   name="video_url" 
                                   value="{{ old('video_url', $match->video_url) }}" 
                                   class="form-control @error('video_url') is-invalid @enderror"
                                   placeholder="https://www.youtube.com/watch?v=...">
                            @error('video_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($match->video_url)
                                <div class="mt-1">
                                    <a href="{{ $match->video_url }}" target="_blank" class="text-decoration-none small">
                                        <i class="bi bi-link-45deg"></i> Current Video
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="col-12 col-lg-4">
                        <div class="card border shadow-sm">
                            <div class="card-body p-3 p-sm-4">

                                {{-- Current Thumbnail --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Current Thumbnail</label>
                                    <div class="border rounded p-2 bg-light text-center">
                                        <img src="{{ asset('storage/'.$match->thumbnail) }}" 
                                             class="img-fluid rounded"
                                             style="max-height: 100px; width: auto; object-fit: contain;">
                                    </div>
                                </div>

                                {{-- Change Thumbnail --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Change Thumbnail</label>
                                    <input type="file" 
                                           name="thumbnail" 
                                           class="form-control @error('thumbnail') is-invalid @enderror" 
                                           accept="image/*">
                                    @error('thumbnail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Leave empty to keep current</small>
                                </div>

                                {{-- Type --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                                    <select name="type" 
                                            class="form-select @error('type') is-invalid @enderror" 
                                            required>
                                        @foreach(['all','match_highlights','press_release','media','blogs'] as $type)
                                            <option value="{{ $type }}"
                                                {{ old('type', $match->type) === $type ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_',' ',$type)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Existing Gallery Images --}}
                                @if($match->images->count() > 0)
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Existing Gallery Images</label>
                                    <div class="row g-2">
                                        @foreach($match->images as $image)
                                            <div class="col-6 text-center">
                                                <img src="{{ asset('storage/'.$image->image) }}"
                                                     class="img-fluid rounded border"
                                                     style="max-height: 60px; width: 100%; object-fit: cover;">
                                                <div class="form-check form-check-inline mt-1">
                                                    <input type="checkbox"
                                                           class="form-check-input"
                                                           name="remove_images[]"
                                                           value="{{ $image->id }}"
                                                           id="remove_img_{{ $image->id }}">
                                                    <label class="form-check-label small" for="remove_img_{{ $image->id }}">
                                                        Remove
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                {{-- Add New Images --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Add More Images</label>
                                    <input type="file" 
                                           name="images[]" 
                                           class="form-control @error('images.*') is-invalid @enderror" 
                                           multiple>
                                    @error('images.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Add additional images to gallery</small>
                                </div>

                                {{-- Preview --}}
                                <div class="mb-3 text-center">
                                    <label class="form-label fw-semibold d-block">New Thumbnail Preview</label>
                                    <div class="border rounded p-2 bg-light" id="previewContainer">
                                        <div id="previewPlaceholder" class="text-center text-muted">
                                            <i class="bi bi-image fs-1 d-block mb-2"></i>
                                            <small>New thumbnail preview</small>
                                        </div>
                                        <img id="imagePreview" class="img-fluid d-none" style="max-height: 120px; width: 100%; object-fit: cover; border-radius: 4px;">
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-check-circle me-1"></i> Update Match
                                    </button>
                                    <a href="{{ route('admin.matches.index') }}" class="btn btn-outline-secondary">
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
    let editors = [];

    function initEditor(textarea) {
        if (textarea && !textarea.hasAttribute('data-editor')) {
            ClassicEditor.create(textarea, {
                toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'link', 'undo', 'redo']
            }).then(editor => {
                editors.push(editor);
                textarea.setAttribute('data-editor', 'true');
            }).catch(error => console.error(error));
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize CKEditor
        document.querySelectorAll('.rich-editor').forEach(textarea => {
            initEditor(textarea);
        });

        // Sync editors before form submission
        const form = document.querySelector('form');
        form.addEventListener('submit', function() {
            editors.forEach(editor => {
                editor.updateSourceElement();
            });
        });

        // Thumbnail Preview
        const fileInput = document.querySelector('input[name="thumbnail"]');
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