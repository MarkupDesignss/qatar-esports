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
    <h4  style="font-size: 1.7rem;font-weight:600;">Add Featured Event</h4>

    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Image 2</label>
            <input type="file" name="image_second" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>
                <input type="checkbox" name="status" value="1" checked> Active
            </label>
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
