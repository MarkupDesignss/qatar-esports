@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3>News List</h3>
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary">Add News</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Thumbnail</th>
                <th>Title</th>
                <th>Tournament</th>
                <th>Type</th>
                <th>Created At</th>
                <th width="150">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($news as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($item->thumbnail)
                            <img src="{{ asset('storage/'.$item->thumbnail) }}" width="60">
                        @endif
                    </td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->tournament?->title ?? '-' }}</td>
                    <td>{{ ucfirst($item->type) }}</td>
                    <td>{{ $item->created_at->format('d M Y') }}</td>
                    <td>
                    {{-- Edit --}}
                    <a href="{{ route('admin.news.edit', $item->id) }}"
                       class="btn btn-sm btn-warning"
                       data-bs-toggle="tooltip"
                       title="Edit News">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                
                    {{-- Delete --}}
                    <form action="{{ route('admin.news.destroy', $item->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Delete this news?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                data-bs-toggle="tooltip"
                                title="Delete News">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>

                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No News Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
