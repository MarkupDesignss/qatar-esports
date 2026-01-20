@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 style="font-size: 1.7rem;font-weight:600;">Create Partner</h2>

    <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Logo</label>
            <input type="file" name="logo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Sort Order</label>
            <input type="number" name="sort_order" class="form-control" value="0">
        </div>

        <div class="mb-3">
            <label>
                <input type="checkbox" name="status" value="1" checked> Active
            </label>
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
