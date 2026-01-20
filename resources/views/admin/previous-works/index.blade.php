@extends('layouts.admin')

@section('content')
<div class="container">
        <div class="d-flex justify-content-between mb-3">
        <h3 style="font-size: 1.7rem;font-weight:600;">Previous Work</h3>
        <a href="{{ route('admin.previous-works.create') }}" class="btn btn-primary mb-3">Add New</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Date</th>
                <th>Status</th>
                <th width="150">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($works as $work)
            <tr>
                <td><img src="{{ asset('storage/'.$work->image) }}" width="80"></td>
                <td>{{ $work->title }}</td>
                <td>{{ $work->category }}</td>
                <td>{{ $work->event_date }}</td>
                <td>{{ $work->status ? 'Active' : 'Inactive' }}</td>
              <td>
                {{-- Edit --}}
                <a href="{{ route('admin.previous-works.edit', $work) }}"
                   class="btn btn-sm btn-warning"
                   data-bs-toggle="tooltip"
                   title="Edit Previous Work">
                    <i class="bi bi-pencil-square"></i>
                </a>
            
                {{-- Delete --}}
                <form action="{{ route('admin.previous-works.destroy', $work) }}"
                      method="POST"
                      class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger"
                            data-bs-toggle="tooltip"
                            title="Delete Previous Work"
                            onclick="return confirm('Delete?')">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
