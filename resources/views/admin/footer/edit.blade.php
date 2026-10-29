@extends('layouts.admin')

@section('content')
<div class="container-fluid px-3">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <h4 class="mb-0 h5 fw-bold text-gray-800">
            <i class="bi bi-pencil-square me-2"></i>Edit Footer Settings
        </h4>
        <a href="{{ route('admin.footer.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <form action="{{ route('admin.footer.update') }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        {{-- ========== TEXT SECTION ========== --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-text-paragraph me-2"></i>Text &amp; Tagline</h5>
            </div>
            <div class="card-body">
                {{-- Tagline --}}
                <div class="mb-3">
                    <label for="tagline" class="form-label">Tagline</label>
                    <textarea name="tagline" id="tagline" class="form-control" rows="2">{{ old('tagline', $settings->tagline) }}</textarea>
                    @error('tagline')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description with CKEditor Super Build --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="10" class="ckeditor form-control">{{ old('description', $settings->description) }}</textarea>
                    @error('description')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Copyright --}}
                <div class="mb-3">
                    <label for="copyright_text" class="form-label">Copyright Text</label>
                    <input type="text" name="copyright_text" id="copyright_text" class="form-control" value="{{ old('copyright_text', $settings->copyright_text) }}">
                    @error('copyright_text')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ========== CTA SECTION ========== --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-megaphone me-2"></i>Call to Action (CTA)</h5>
            </div>
            <div class="card-body">
                {{-- CTA Heading --}}
                <div class="mb-3">
                    <label for="cta_heading" class="form-label">CTA Heading</label>
                    <input type="text" name="cta_heading" id="cta_heading" class="form-control"
                           value="{{ old('cta_heading', $settings->cta_heading) }}"
                           placeholder="e.g. Ready To Start Your Journey?">
                    @error('cta_heading')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- CTA Quote --}}
                <div class="mb-3">
                    <label for="cta_quote" class="form-label">CTA Quote</label>
                    <textarea name="cta_quote" id="cta_quote" class="form-control" rows="3"
                              placeholder="e.g. If There Is Nothing In A Chest, The Chest Doesn't Mean Anything">{{ old('cta_quote', $settings->cta_quote) }}</textarea>
                    @error('cta_quote')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ========== SOCIAL MEDIA ========== --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-share-fill me-2"></i>Social Media Links</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="discord_url" class="form-label"><i class="bi bi-discord me-1" style="color:#5865F2;"></i> Discord</label>
                            <input type="url" name="discord_url" id="discord_url" class="form-control" value="{{ old('discord_url', $settings->discord_url) }}" placeholder="https://discord.gg/...">
                            @error('discord_url')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="youtube_url" class="form-label"><i class="bi bi-youtube me-1" style="color:#FF0000;"></i> YouTube</label>
                            <input type="url" name="youtube_url" id="youtube_url" class="form-control" value="{{ old('youtube_url', $settings->youtube_url) }}" placeholder="https://youtube.com/...">
                            @error('youtube_url')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="instagram_url" class="form-label"><i class="bi bi-instagram me-1" style="color:#E4405F;"></i> Instagram</label>
                            <input type="url" name="instagram_url" id="instagram_url" class="form-control" value="{{ old('instagram_url', $settings->instagram_url) }}" placeholder="https://instagram.com/...">
                            @error('instagram_url')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="twitter_url" class="form-label"><i class="bi bi-twitter-x me-1"></i> Twitter / X</label>
                            <input type="url" name="twitter_url" id="twitter_url" class="form-control" value="{{ old('twitter_url', $settings->twitter_url) }}" placeholder="https://twitter.com/...">
                            @error('twitter_url')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== CONTACT INFORMATION ========== --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-info-circle-fill me-2"></i>Contact Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label"><i class="bi bi-envelope me-1" style="color:#EA4335;"></i> Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $settings->email) }}" placeholder="info@example.com">
                            @error('email')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="contact_phone" class="form-label"><i class="bi bi-telephone me-1" style="color:#34A853;"></i> Phone</label>
                            <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ old('contact_phone', $settings->contact_phone) }}" placeholder="+974 1234 5678">
                            @error('contact_phone')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="whatsapp_number" class="form-label"><i class="bi bi-whatsapp me-1" style="color:#25D366;"></i> WhatsApp Number</label>
                            <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $settings->whatsapp_number) }}" placeholder="+974 9876 5432">
                            @error('whatsapp_number')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="contact_address" class="form-label"><i class="bi bi-geo-alt me-1" style="color:#4285F4;"></i> Address</label>
                            <textarea name="contact_address" id="contact_address" class="form-control" rows="2">{{ old('contact_address', $settings->contact_address) }}</textarea>
                            @error('contact_address')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== FORM BUTTONS ========== --}}
        <div class="d-flex flex-wrap gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i> Update Settings
            </button>
            <a href="{{ route('admin.footer.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i> Cancel
            </a>
        </div>
    </form>
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
});
</script>
@endsection