@extends('layouts.admin')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h3 style="font-size:1.7rem;font-weight:600;">Add Match Highlight</h3>

    <form action="{{ route('admin.matches.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        {{-- Title --}}
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text"
                   name="title"
                   value="{{ old('title') }}"
                   class="form-control"
                   required>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label class="form-label">Short Description</label>
            <textarea name="description"
                      class="form-control"
                      rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Thumbnail</label>
            <input type="file" name="thumbnail" class="form-control" required>
        </div>

        {{-- Type --}}
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type"
                    class="form-select @error('type') is-invalid @enderror"
                    required>
                <option value="">Select Type</option>
                @foreach(['all','match_highlights','press_release','media','blogs'] as $type)
                    <option value="{{ $type }}"
                        {{ old('type') === $type ? 'selected' : '' }}>
                        {{ ucwords(str_replace('_',' ',$type)) }}
                    </option>
                @endforeach
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Video --}}
        <div class="mb-3">
            <label class="form-label">Video Title</label>
            <input type="text"
                   name="video_title"
                   value="{{ old('video_title') }}"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Video URL (optional)</label>
            <input type="url"
                   name="video_url"
                   value="{{ old('video_url') }}"
                   class="form-control">
        </div>

        {{-- Gallery Images --}}
        <div class="mb-3">
            <label class="form-label">Gallery Images</label>
            <input type="file"
                   name="images[]"
                   class="form-control"
                   multiple
                   required>
            <small class="text-muted">
                You can upload multiple images
            </small>
        </div>

        {{-- Content Sections --}}
        <hr>
        <h5>Content Sections</h5>

        <div id="content-sections">
            <div class="content-block mb-3">
                <input type="text"
                       name="contents[0][heading]"
                       class="form-control mb-2"
                       placeholder="Section Heading (optional)">

                <textarea name="contents[0][content]"
                          class="form-control"
                          rows="4"
                          placeholder="Section content"
                          required></textarea>
            </div>
        </div>

        <button type="button"
                class="btn btn-sm btn-outline-primary"
                onclick="addBlock()">
            + Add Another Section
        </button>

        <br><br>

        {{-- Actions --}}
        <button class="btn btn-success">Save</button>
        <a href="{{ route('admin.matches.index') }}"
           class="btn btn-secondary">Back</a>
    </form>
</div>

<script>
let index = 1;
function addBlock() {
    const html = `
        <div class="content-block mb-3">
            <input type="text"
                   name="contents[${index}][heading]"
                   class="form-control mb-2"
                   placeholder="Section Heading (optional)">
            <textarea name="contents[${index}][content]"
                      class="form-control"
                      rows="4"
                      placeholder="Section content"
                      required></textarea>
        </div>`;
    document.getElementById('content-sections')
            .insertAdjacentHTML('beforeend', html);
    index++;
}
</script>
@endsection
