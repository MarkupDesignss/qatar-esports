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

    <h3  style="font-size: 1.7rem;font-weight:600;">Add Challenge</h3>

    <form action="{{ route('admin.challenge.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Heading</label>
            <input type="text" name="heading" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Content</label>
            <textarea name="content" rows="5" class="form-control" required></textarea>
        </div>
        
          <div class="mb-3">
            <label class="form-label">Thumbnail</label>
            <input type="file" name="thumbnail" class="form-control">
        </div>

        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="mb-3">
            <label>Video URL</label>
            <input type="url" name="video_url" class="form-control">
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('admin.challenge.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
