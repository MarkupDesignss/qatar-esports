@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0" style="font-size: 1.7rem;font-weight:600;">Edit Previous Work</h5>
            <a href="{{ route('admin.previous-works.index') }}" class="btn btn-sm btn-secondary">
                Back
            </a>
        </div>

        <div class="card-body">
            <form method="POST" enctype="multipart/form-data"
                  action="{{ route('admin.previous-works.update', $previousWork) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <input class="form-control" name="category"
                               value="{{ old('category', $previousWork->category) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Title</label>
                        <input class="form-control" name="title"
                               value="{{ old('title', $previousWork->title) }}" >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Event Date</label>
                        <input type="date" class="form-control" name="event_date"
                               value="{{ old('event_date', $previousWork->event_date) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Video URL</label>
                        <input class="form-control" name="video_url"
                               value="{{ old('video_url', $previousWork->video_url) }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="4"
                                  name="description">{{ old('description', $previousWork->description) }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Current Image</label><br>
                        <img src="{{ asset('storage/'.$previousWork->image) }}"
                             class="img-thumbnail mb-2" width="180">
                        <input type="file" class="form-control" name="image">
                        <small class="text-muted">Leave empty to keep existing image</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status">
                            <option value="1" @selected($previousWork->status)>Active</option>
                            <option value="0" @selected(!$previousWork->status)>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <button class="btn btn-primary px-4">
                        Update Work
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
