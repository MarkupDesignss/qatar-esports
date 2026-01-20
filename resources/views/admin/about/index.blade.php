@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h4>About Sections</h4>
        <a href="{{ route('admin.about.create') }}" class="btn btn-primary">
            Add Section
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Type</th>
                <th>Title</th>
                <th>Status</th>
                <th width="140">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sections as $section)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    {{-- Image --}}
                    <td>
                        @if($section->image)
                            <img src="{{ asset('storage/'.$section->image) }}"
                                 width="80"
                                 class="rounded border">
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </td>

                    <td class="text-capitalize">{{ $section->type }}</td>
                    <td>{{ $section->title }}</td>

                    <td>
                        @if($section->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td>
                        {{-- Edit --}}
                        <a href="{{ route('admin.about.edit', $section->id) }}"
                           class="btn btn-sm btn-warning"
                           data-bs-toggle="tooltip"
                           title="Edit Section">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        {{-- Delete --}}
                        <form action="{{ route('admin.about.destroy', $section->id) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    data-bs-toggle="tooltip"
                                    title="Delete Section">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No Data Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
