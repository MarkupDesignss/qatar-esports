@extends('layouts.admin')

@section('content')
<div class="container-fluid">
{{-- SUCCESS MESSAGE --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ERROR MESSAGE --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- VALIDATION ERRORS --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Something went wrong:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

    <div class="d-flex justify-content-between mb-3">
        <h4 class="fw-bold">Banners</h4>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
            Add Banner
        </a>
    </div>


    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th width="120">Image</th>
                <th>Heading</th>
                <th>Description</th>
                <th width="150">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($banners as $banner)
                <tr>
                    <td>
                        <img src="{{ asset('storage/' . $banner->image) }}"
                             style="height:60px">
                    </td>
                    <td>{{ $banner->heading }}</td>
                    <td>{{ Str::limit($banner->description, 50) }}</td>
                    <td>
                        {{-- Edit --}}
                        <a href="{{ route('admin.banners.edit', $banner) }}"
                           class="btn btn-sm btn-primary"
                           data-bs-toggle="tooltip"
                           title="Edit Banner">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    
                        {{-- Delete --}}
                        <form action="{{ route('admin.banners.destroy', $banner) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    data-bs-toggle="tooltip"
                                    title="Delete Banner"
                                    onclick="return confirm('Delete this banner?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        No banners found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
