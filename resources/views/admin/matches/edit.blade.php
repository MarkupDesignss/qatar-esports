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
    <h3>Edit Match Highlight</h3>

    <form action="{{ route('admin.matches.update', $match) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" value="{{ $match->title }}" class="form-control">
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $match->description }}</textarea>
        </div>

          <div class="mb-3">
            <label class="form-label">Current Thumbnail</label><br>
            <img src="{{ asset('storage/'.$match->thumbnail) }}" width="120">
        </div>

        <div class="mb-3">
            <label class="form-label">Change Thumbnail</label>
            <input type="file" name="thumbnail" class="form-control">
        </div>

        {{-- Type --}}
        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-select">
                @foreach(['all','match_highlights','press_release','media','blogs'] as $type)
                    <option value="{{ $type }}"
                        {{ $match->type === $type ? 'selected' : '' }}>
                        {{ ucwords(str_replace('_',' ',$type)) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Video --}}
        <div class="mb-3">
            <label>Video Title</label>
            <input type="text" name="video_title" value="{{ $match->video_title }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Video URL</label>
            <input type="url" name="video_url" value="{{ $match->video_url }}" class="form-control">
        </div>

        {{-- Existing Images --}}
        <div class="mb-3">
            <label>Existing Images</label>
            <div class="row">
                @foreach($match->images as $image)
                    <div class="col-md-3 text-center mb-3">
                        <img src="{{ asset('storage/'.$image->image) }}"
                             class="img-fluid rounded mb-1">
                        <div>
                            <input type="checkbox"
                                   name="remove_images[]"
                                   value="{{ $image->id }}">
                            Remove
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Add New Images --}}
        <div class="mb-3">
            <label>Add More Images</label>
            <input type="file" name="images[]" multiple class="form-control">
        </div>

        {{-- Content Sections --}}
        <h5>Content Sections</h5>
        <div id="content-sections">
            @foreach($match->contents as $i => $section)
                <div class="mb-3">
                    <input type="text"
                           name="contents[{{ $i }}][heading]"
                           value="{{ $section->heading }}"
                           class="form-control mb-2"
                           placeholder="Heading">

                    <textarea name="contents[{{ $i }}][content]"
                              class="form-control"
                              rows="4">{{ $section->content }}</textarea>
                </div>
            @endforeach
        </div>

        <button type="button"
                class="btn btn-sm btn-outline-primary"
                onclick="addBlock()">+ Add Section</button>

        <br><br>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.matches.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

<script>
let index = {{ $match->contents->count() }};
function addBlock() {
    const html = `
        <div class="mb-3">
            <input type="text" name="contents[${index}][heading]" class="form-control mb-2">
            <textarea name="contents[${index}][content]" class="form-control" rows="4"></textarea>
        </div>`;
    document.getElementById('content-sections').insertAdjacentHTML('beforeend', html);
    index++;
}
</script>
@endsection
