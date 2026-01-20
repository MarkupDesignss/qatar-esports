@extends('layouts.admin')

@section('content')
<div class="container">
    <h4 class="mb-3">Edit About Section</h4>

    <form action="{{ route('admin.about.update', $section->id) }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-control" required>
                <option value="mission" {{ $section->type == 'mission' ? 'selected' : '' }}>Mission</option>
                <option value="vision" {{ $section->type == 'vision' ? 'selected' : '' }}>Vision</option>
                <option value="goals" {{ $section->type == 'goals' ? 'selected' : '' }}>Goals</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control"
                   value="{{ $section->title }}" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" rows="5"
                      class="form-control" required>{{ $section->description }}</textarea>
        </div>

        <div class="mb-3">
            <label>Video URL</label>
            <input type="url" name="video_url"
                   class="form-control" value="{{ $section->video_url }}">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="1" {{ $section->status ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$section->status ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Image</label><br>
            @if($section->image)
                <img src="{{ asset('storage/'.$section->image) }}"
                     width="120" class="mb-2">
            @endif
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.about.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
