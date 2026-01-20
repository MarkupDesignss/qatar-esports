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
    <h4 style="font-size: 1.7rem;font-weight:600;">Edit Featured Event</h4>

    <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="{{ $event->title }}" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4" required>{{ $event->description }}</textarea>
        </div>


        <div class="mb-3">
            <label>Change Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="mb-3">
            <label>Second Image</label><br>
            <img src="{{ asset('storage/'.$event->image_second) }}" width="120">
        </div>
        <div class="mb-3">
            <label>Change second Image</label>
            <input type="file" name="image_second" class="form-control">
        </div>

        <div class="mb-3">
            <label>
                <input type="checkbox" name="status" value="1" {{ $event->status ? 'checked' : '' }}>
                Active
            </label>
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
