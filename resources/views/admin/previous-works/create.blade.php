@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0" style="font-size: 1.7rem;font-weight:600;">Add Previous Work</h5>
            <a href="{{ route('admin.previous-works.index') }}" class="btn btn-sm btn-secondary">
                Back
            </a>
        </div>

        <div class="card-body">
            <form method="POST" enctype="multipart/form-data"
                  action="{{ route('admin.previous-works.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <input class="form-control" name="category"
                               placeholder="Valorant Seasons">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Title</label>
                        <input class="form-control" name="title"
                               placeholder="Event Title">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Event Date</label>
                        <input type="date" class="form-control" name="event_date">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Video URL</label>
                        <input class="form-control" name="video_url"
                               placeholder="https://youtube.com/...">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="4"
                                  name="description"
                                  placeholder="Short description of the event"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <button class="btn btn-success px-4">
                        Save Work
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
