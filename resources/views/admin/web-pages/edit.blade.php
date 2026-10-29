@extends('layouts.admin')
@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 style="font-size: 1.5rem; color: #0d6efd;" class="mb-0 fw-bold">Edit Web Page</h3>
        <a href="{{ route('admin.web.page.list') }}" class="btn btn-primary btn-custom-add">
            <i class="bi bi-arrow-left me-1"></i>Back to List
        </a>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.web.page.update',$web_page->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Title</label><span class="text-danger">*</span>
                        <input type="text" name="title" id="title" value="{{ old('title',$web_page->title) }}" class="form-control">
                        @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Slug</label><span class="text-danger">*</span>
                        <input type="text" name="slug" id="slug" value="{{ old('slug',$web_page->slug) }}" readonly class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Banner Image</label>
                        <input type="file" name="image" class="form-control">
                        @if($web_page->banner_image)
                            <img src="{{ asset('storage/web_pages/'.$web_page->banner_image) }}" class="mt-2 rounded" width="50">
                        @endif
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ $web_page->status==1?'selected':'' }}>Active</option>
                            <option value="0" {{ $web_page->status==0?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button class="btn btn-success">Update</button>
                    <a href="{{ route('admin.web.page.list') }}" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('title').addEventListener('keyup', function() {
    let slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'');
    document.getElementById('slug').value = slug;
});
</script>
@endsection
