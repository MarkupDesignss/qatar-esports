@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header" style="font-size: 1.7rem;font-weight:600;">Edit Service</div>

    <div class="card-body">
        <form action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Title</label>
                <input name="title" class="form-control" value="{{ old('title', $service->title) }}">
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ old('description', $service->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label>Current Image</label><br>
                <img src="{{ asset('storage/'.$service->image) }}" width="120" class="mb-2">
                <input type="file" name="image" class="form-control">
            </div>

            <!--<div class="mb-3">-->
            <!--    <label>Button Text</label>-->
            <!--    <input name="button_text" class="form-control" value="{{ old('button_text', $service->button_text) }}">-->
            <!--</div>-->

            <!--<div class="mb-3">-->
            <!--    <label>Button Link</label>-->
            <!--    <input name="button_link" class="form-control" value="{{ old('button_link', $service->button_link) }}">-->
            <!--</div>-->

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="1" @selected($service->status)>Active</option>
                    <option value="0" @selected(!$service->status)>Inactive</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Sort Order</label>
                <input name="sort_order" type="number" class="form-control" value="{{ $service->sort_order }}">
            </div>

            <button class="btn btn-primary">Update Service</button>
        </form>
    </div>
</div>
@endsection
