@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header" style="font-size: 1.7rem;font-weight:600;">Add Service</div>

    <div class="card-body">
        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Title</label>
                <input name="title" class="form-control" value="{{ old('title') }}">
                @error('title') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label>Image</label>
                <input type="file" name="image" class="form-control">
            </div>

            <!--<div class="mb-3">-->
            <!--    <label>Button Text</label>-->
            <!--    <input name="button_text" class="form-control" value="{{ old('button_text') }}">-->
            <!--</div>-->

            <!--<div class="mb-3">-->
            <!--    <label>Button Link</label>-->
            <!--    <input name="button_link" class="form-control" value="{{ old('button_link') }}">-->
            <!--</div>-->

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Sort Order</label>
                <input name="sort_order" type="number" class="form-control" value="0">
            </div>

            <button class="btn btn-success">Create Service</button>
        </form>
    </div>
</div>
@endsection
