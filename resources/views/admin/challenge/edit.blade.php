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

    <h3 style="font-size: 1.7rem;font-weight:600;">Edit Challenge</h3>

    <form action="{{ route('admin.challenge.update', $challenge->id) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Heading</label>
            <input type="text"
                   name="heading"
                   value="{{ old('heading', $challenge->heading) }}"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content"
                      rows="5"
                      class="form-control"
                      required>{{ old('content', $challenge->content) }}</textarea>
        </div>

        {{-- Image --}}
        <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control">
            @if($challenge->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/'.$challenge->image) }}" width="120">
                </div>
            @endif
        </div>

        {{-- Thumbnail --}}
        <div class="mb-3">
            <label class="form-label">Thumbnail</label>
            <input type="file" name="thumbnail" class="form-control">
            @if($challenge->thumbnail)
                <div class="mt-2">
                    <img src="{{ asset('storage/'.$challenge->thumbnail) }}" width="120">
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Video URL</label>
            <input type="url"
                   name="video_url"
                   value="{{ old('video_url', $challenge->video_url) }}"
                   class="form-control">
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('admin.challenge.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
