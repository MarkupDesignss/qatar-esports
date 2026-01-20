@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 style="font-size: 1.7rem;font-weight:600;">Edit Partner</h2>

    <form action="{{ route('admin.partners.update', $partner) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $partner->name }}" required>
        </div>

        <div class="mb-3">
            <label>Current Logo</label><br>
            <img src="{{ asset('storage/'.$partner->logo) }}" width="80">
        </div>

        <div class="mb-3">
            <label>Change Logo</label>
            <input type="file" name="logo" class="form-control">
        </div>

        <div class="mb-3">
            <label>Sort Order</label>
            <input type="number" name="sort_order" class="form-control" value="{{ $partner->sort_order }}">
        </div>

        <div class="mb-3">
            <label>
                <input type="checkbox" name="status" value="1" {{ $partner->status ? 'checked' : '' }}>
                Active
            </label>
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
