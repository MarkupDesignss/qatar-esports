@extends('layouts.admin')

@section('content')
<div class="container">
    <h4 class="mb-3">Add About Section</h4>

    <form action="{{ route('admin.about.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-control" required>
                <option value="">Select Type</option>
                <option value="mission">Mission</option>
                <option value="vision">Vision</option>
                <option value="goals">Goals</option>
            </select>
            @error('type') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control"
                   value="{{ old('title') }}" required>
            @error('title') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" rows="5"
                      class="form-control" required>{{ old('description') }}</textarea>
            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Video URL (Optional)</label>
            <input type="url" name="video_url" class="form-control"
                   value="{{ old('video_url') }}">
        </div>

        <div class="mb-3">
            <label>Image (Optional)</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('admin.about.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
