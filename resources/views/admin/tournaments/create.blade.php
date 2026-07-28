@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                    <i class="fas fa-trophy me-2 text-primary"></i>Create New Tournament
                </h2>
                <p class="text-muted mb-0">Add a new tournament to the platform</p>
            </div>
            <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.tournaments.store') }}" enctype="multipart/form-data"
                class="needs-validation" novalidate>
                @csrf

                <div class="row">
                    {{-- LEFT SIDE --}}
                    <div class="col-md-8">

                        {{-- Tournament Title --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tournament Title *</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Slug</label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                value="{{ old('slug') }}">
                            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Game --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Game</label>
                            <select name="game_id" class="form-select @error('game_id') is-invalid @enderror">
                                <option value="">Select Game</option>
                                @foreach($games as $game)
                                <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                                    {{ $game->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('game_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Logo --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tournament Logo</label>
                            <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror"
                                accept="image/*">
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Banner --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tournament Banner</label>
                            <input type="file" name="banner" class="form-control @error('banner') is-invalid @enderror"
                                accept="image/*">
                            @error('banner') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Format --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Format</label>
                            <select name="format" class="form-select">
                                <option value="">Select Format</option>
                                <option value="solo" {{ old('format') == 'solo' ? 'selected' : '' }}>Solo</option>
                                <option value="team" {{ old('format') == 'team' ? 'selected' : '' }}>Team</option>
                            </select>
                        </div>

                        {{-- Team Size --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Team Size</label>
                            <input type="number" name="team_size" id="team_size" min="1" class="form-control"
                                value="{{ old('team_size') }}">
                        </div>

                        {{-- Description with CKEditor --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="description" rows="10"
                                class="ckeditor form-control">{{ old('description') }}</textarea>
                        </div>

                        {{-- Rules with CKEditor --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rules</label>
                            <textarea name="rules" id="rules" rows="10"
                                class="ckeditor form-control">{{ old('rules') }}</textarea>
                        </div>

                    </div>

                    {{-- RIGHT SIDE --}}
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body">

                                @php
                                    $now = now()->format('Y-m-d\TH:i');
                                @endphp

                                {{-- Visibility --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Visibility</label>
                                    <select name="visibility" class="form-select">
                                        <option value="draft" {{ old('visibility') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('visibility','published') == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="archived" {{ old('visibility') == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                </div>

                                {{-- Featured --}}
                                <input type="hidden" name="is_featured" value="0">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold">Featured</label>
                                </div>

                                {{-- Allow PDF Download --}}
                                <input type="hidden" name="allow_pdf_download" value="0">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="allow_pdf_download" value="1" {{ old('allow_pdf_download') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold">Allow PDF Download</label>
                                    <small class="d-block text-muted">Enable to let users download Rules PDF</small>
                                </div>

                                {{-- Registration Dates --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Registration Start</label>
                                    <input type="datetime-local" name="registration_start" 
                                           class="form-control @error('registration_start') is-invalid @enderror" 
                                           value="{{ old('registration_start') }}"
                                           min="{{ $now }}">
                                    @error('registration_start') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Registration End</label>
                                    <input type="datetime-local" name="registration_end" 
                                           class="form-control @error('registration_end') is-invalid @enderror" 
                                           value="{{ old('registration_end') }}"
                                           min="{{ $now }}">
                                    @error('registration_end') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Playoff Dates --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Playoff Start</label>
                                    <input type="datetime-local" name="start_date" 
                                           class="form-control @error('start_date') is-invalid @enderror" 
                                           value="{{ old('start_date') }}"
                                           min="{{ $now }}">
                                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Playoff End</label>
                                    <input type="datetime-local" name="end_date" 
                                           class="form-control @error('end_date') is-invalid @enderror" 
                                           value="{{ old('end_date') }}"
                                           min="{{ $now }}">
                                    @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Entry Fee --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Entry Fee</label>
                                    <input type="number" name="entry_fee" min="0" class="form-control" value="{{ old('entry_fee') }}">
                                </div>

                                {{-- Prize Pool --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Prize Pool</label>
                                    <input type="number" name="prize_pool" min="0" class="form-control" value="{{ old('prize_pool') }}">
                                </div>

                                {{-- Max Participants --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Max Participants</label>
                                    <input type="number" name="max_participants" min="1" class="form-control" value="{{ old('max_participants') }}">
                                </div>

                                {{-- ═══════════════════════════════════════════════════════════ --}}
                                {{-- 🆕 NEW: SOCIAL LINKS SECTION --}}
                                {{-- ═══════════════════════════════════════════════════════════ --}}

                                <hr class="my-3">

                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-share-alt me-2"></i>Social Links
                                </h6>

                                {{-- YouTube --}}
                                <div class="mb-2">
                                    <label class="form-label text-sm">YouTube</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-youtube text-danger"></i></span>
                                        <input type="url" 
                                               name="social_links[youtube]" 
                                               class="form-control @error('social_links.youtube') is-invalid @enderror"
                                               value="{{ old('social_links.youtube') }}"
                                               placeholder="https://youtube.com/...">
                                    </div>
                                    @error('social_links.youtube') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Twitch --}}
                                <div class="mb-2">
                                    <label class="form-label text-sm">Twitch</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-twitch text-purple"></i></span>
                                        <input type="url" 
                                               name="social_links[twitch]" 
                                               class="form-control @error('social_links.twitch') is-invalid @enderror"
                                               value="{{ old('social_links.twitch') }}"
                                               placeholder="https://twitch.tv/...">
                                    </div>
                                    @error('social_links.twitch') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Instagram --}}
                                <div class="mb-2">
                                    <label class="form-label text-sm">Instagram</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-instagram text-pink"></i></span>
                                        <input type="url" 
                                               name="social_links[instagram]" 
                                               class="form-control @error('social_links.instagram') is-invalid @enderror"
                                               value="{{ old('social_links.instagram') }}"
                                               placeholder="https://instagram.com/...">
                                    </div>
                                    @error('social_links.instagram') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Facebook --}}
                                <div class="mb-2">
                                    <label class="form-label text-sm">Facebook</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-facebook text-primary"></i></span>
                                        <input type="url" 
                                               name="social_links[facebook]" 
                                               class="form-control @error('social_links.facebook') is-invalid @enderror"
                                               value="{{ old('social_links.facebook') }}"
                                               placeholder="https://facebook.com/...">
                                    </div>
                                    @error('social_links.facebook') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Discord --}}
                                <div class="mb-2">
                                    <label class="form-label text-sm">Discord</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-discord text-indigo"></i></span>
                                        <input type="url" 
                                               name="social_links[discord]" 
                                               class="form-control @error('social_links.discord') is-invalid @enderror"
                                               value="{{ old('social_links.discord') }}"
                                               placeholder="https://discord.gg/...">
                                    </div>
                                    @error('social_links.discord') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- TikTok (optional) --}}
                                <div class="mb-2">
                                    <label class="form-label text-sm">TikTok</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-tiktok"></i></span>
                                        <input type="url" 
                                               name="social_links[tiktok]" 
                                               class="form-control @error('social_links.tiktok') is-invalid @enderror"
                                               value="{{ old('social_links.tiktok') }}"
                                               placeholder="https://tiktok.com/@...">
                                    </div>
                                    @error('social_links.tiktok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Twitter / X --}}
                                <div class="mb-2">
                                    <label class="form-label text-sm">Twitter / X</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                        <input type="url" 
                                               name="social_links[twitter]" 
                                               class="form-control @error('social_links.twitter') is-invalid @enderror"
                                               value="{{ old('social_links.twitter') }}"
                                               placeholder="https://twitter.com/...">
                                    </div>
                                    @error('social_links.twitter') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- ═══════════════════════════════════════════════════════════ --}}
                                {{-- 🆕 MAIN STREAM URL --}}
                                {{-- ═══════════════════════════════════════════════════════════ --}}

                                <hr class="my-3">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Main Stream URL (Live Broadcast)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-video"></i></span>
                                        <input type="url" 
                                               name="stream_url" 
                                               class="form-control @error('stream_url') is-invalid @enderror"
                                               value="{{ old('stream_url') }}"
                                               placeholder="https://youtube.com/live/... or https://twitch.tv/...">
                                    </div>
                                    <small class="text-muted">This will be shown as a prominent "Watch Live" button.</small>
                                    @error('stream_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- ═══════════════════════════════════════════════════════════ --}}
                                {{-- END OF NEW FIELDS --}}
                                {{-- ═══════════════════════════════════════════════════════════ --}}

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-save me-1"></i> Save Tournament
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

{{-- CKEditor 5 CDN --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    // Team size logic (unchanged)
    document.addEventListener('DOMContentLoaded', function() {
        const formatSelect = document.querySelector('select[name="format"]');
        const teamSizeInput = document.getElementById('team_size');

        function updateTeamSizeField() {
            if (formatSelect.value === 'solo') {
                teamSizeInput.value = 1;
                teamSizeInput.disabled = true;
            } else {
                teamSizeInput.disabled = false;
            }
        }

        updateTeamSizeField();
        formatSelect.addEventListener('change', updateTeamSizeField);
    });

    // CKEditor initialization
    document.addEventListener('DOMContentLoaded', function() {
        const editors = [];

        // Find all textareas with class 'ckeditor'
        document.querySelectorAll('.ckeditor').forEach(textarea => {
            ClassicEditor
                .create(textarea, {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'underline', 'strikethrough', '|',
                            'bulletedList', 'numberedList', '|',
                            'alignment', '|',
                            'link', 'blockQuote', '|',
                            'undo', 'redo'
                        ]
                    },
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                        ]
                    },
                    language: 'en'
                })
                .then(editor => {
                    editors.push(editor);
                    textarea.setAttribute('data-editor-initialized', 'true');
                })
                .catch(error => console.error(error));
        });

        // Sync editor content to original textarea before form submission
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', () => {
                editors.forEach(editor => editor.updateSourceElement());
            });
        }
    });
</script>
@endsection