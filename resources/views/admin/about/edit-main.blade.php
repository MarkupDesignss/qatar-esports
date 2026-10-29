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
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Main About Page
                </h4>
                <a href="{{ route('admin.about.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body p-3 p-sm-4">
            <form action="{{ route('admin.about.update-main') }}"
                  method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 col-lg-8">

                        {{-- Heading --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Heading <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="heading" 
                                   class="form-control form-control-sm @error('heading') is-invalid @enderror"
                                   value="{{ old('heading', $about->heading) }}" 
                                   placeholder="Enter main heading"
                                   required>
                            @error('heading') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Description with CKEditor Super Build --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="10"
                                      class="ckeditor form-control form-control-sm @error('description') is-invalid @enderror"
                                      placeholder="Enter main description"
                                      required>{{ old('description', $about->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Badge --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Badge</label>
                            <input type="text" 
                                   name="badge" 
                                   class="form-control form-control-sm @error('badge') is-invalid @enderror"
                                   value="{{ old('badge', $about->badge) }}"
                                   placeholder="e.g., Since 2020">
                            @error('badge') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="col-12 col-lg-4">
                        <div class="card border shadow-sm">
                            <div class="card-body p-3 p-sm-4">

                                {{-- Current Image --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Current Image</label>
                                    <div class="border rounded p-2 bg-light text-center">
                                        @if($about->image)
                                            <img src="{{ asset('storage/'.$about->image) }}"
                                                 class="img-fluid rounded"
                                                 style="max-height: 100px; width: auto; object-fit: contain;">
                                        @else
                                            <span class="text-muted small">No image uploaded</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Change Image --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Change Image</label>
                                    <input type="file" 
                                           name="image" 
                                           class="form-control form-control-sm @error('image') is-invalid @enderror">
                                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <small class="text-muted d-block mt-1">
                                        Leave empty to keep current. Allowed: jpg, png, gif, svg, webp (max 2MB)
                                    </small>
                                </div>

                                {{-- Preview --}}
                                <div class="mb-3 text-center">
                                    <label class="form-label fw-semibold d-block">New Image Preview</label>
                                    <div class="border rounded p-2 bg-light" id="previewContainer">
                                        <div id="previewPlaceholder" class="text-center text-muted">
                                            <i class="bi bi-image fs-1 d-block mb-2"></i>
                                            <small>New image preview</small>
                                        </div>
                                        <img id="imagePreview" class="img-fluid d-none" style="max-height: 120px; width: 100%; object-fit: contain; border-radius: 4px;">
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-check-circle me-1"></i> Update Main
                                    </button>
                                    <a href="{{ route('admin.about.index') }}" class="btn btn-secondary btn-sm">
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

{{-- CKEditor 5 Super Build (Same as all other admin forms) --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/super-build/ckeditor.js"></script>
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/41.4.2/super-build/ckeditor.css">

<style>
.ck-content ul,
.ck-editor__editable ul {
    list-style-type: disc !important;
    padding-left: 2rem !important;
    margin: 0.5rem 0 !important;
}
.ck-content ol,
.ck-editor__editable ol {
    list-style-type: decimal !important;
    padding-left: 2rem !important;
    margin: 0.5rem 0 !important;
}
.ck-content li,
.ck-editor__editable li {
    display: list-item !important;
    margin-bottom: 0.25rem !important;
}
.ck-content ul ul, .ck-editor__editable ul ul { list-style-type: circle !important; }
.ck-content ul ul ul, .ck-editor__editable ul ul ul { list-style-type: square !important; }
.ck-content ol ol, .ck-editor__editable ol ol { list-style-type: lower-alpha !important; }
.ck-content ol ol ol, .ck-editor__editable ol ol ol { list-style-type: lower-roman !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ===== CKEditor Super Build Init =====
    const editors = [];

    document.querySelectorAll('.ckeditor').forEach(textarea => {
        CKEDITOR.ClassicEditor
            .create(textarea, {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'bulletedList', 'numberedList', '|',
                        'alignment', '|',
                        'link', 'blockQuote', 'insertTable', '|',
                        'undo', 'redo'
                    ],
                    shouldNotGroupWhenFull: true
                },
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                    ]
                },
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                alignment: {
                    options: ['left', 'center', 'right', 'justify']
                },
                language: 'en',
                removePlugins: [
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'RealTimeCollaborativeEditing',
                    'PresenceList',
                    'Comments',
                    'CommentsArchive',
                    'TrackChanges',
                    'TrackChangesData',
                    'TrackChangesEditing',
                    'RevisionHistory',
                    'Collaboration',
                    'Pagination',
                    'WProofreader',
                    'MathType',
                    'SlashCommand',
                    'Template',
                    'DocumentOutline',
                    'FormatPainter',
                    'TableOfContents',
                    'ExportWord',
                    'ExportPdf',
                    'ImportWord',
                    'AIAssistant',
                    'OpenAI',
                    'CaseChange',
                    'MultiLevelList',
                    'PasteFromOfficeEnhanced',
                    'PasteFromOffice'
                ]
            })
            .then(editor => {
                editors.push(editor);
                textarea.setAttribute('data-editor-initialized', 'true');
            })
            .catch(error => {
                console.error('CKEditor init error:', error);
            });
    });

    // ===== Sync editors before submit =====
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', () => {
            editors.forEach(editor => editor.updateSourceElement());
        });
    }

    // ===== Image Preview =====
    const fileInput = document.querySelector('input[name="image"]');
    const previewPlaceholder = document.getElementById('previewPlaceholder');
    const imagePreview = document.getElementById('imagePreview');

    if (fileInput && imagePreview && previewPlaceholder) {
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
    }
});
</script>
@endsection