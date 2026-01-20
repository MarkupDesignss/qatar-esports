@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3 style="font-size: 1.7rem;font-weight:600;">Match Highlights</h3>
        <a href="{{ route('admin.matches.create') }}" class="btn btn-primary">
            Add Match
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Thumbnail</th>
                <th>Title</th>
                <!--<th>Video Title</th>-->
                <!--<th>Video URL</th>-->
                <th width="160">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($matches as $match)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="{{ asset('storage/'.$match->thumbnail) }}" width="80">
                    </td>
                    <td>{{ $match->title }}</td>
                    <!--<td>{{ $match->video_title }}</td>-->
                    <!--<td>-->
                    <!--    @if($match->video_url)-->
                    <!--    <a href="{{ $match->video_url }}" target="_blank">View</a>-->
                    <!--    @endif-->
                    <!--</td>-->
                    <td>
                    {{-- Edit --}}
                    <a href="{{ route('admin.matches.edit', $match) }}"
                       class="btn btn-sm btn-warning"
                       data-bs-toggle="tooltip"
                       title="Edit Match">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                
                    {{-- Delete --}}
                    <form action="{{ route('admin.matches.destroy', $match) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                data-bs-toggle="tooltip"
                                title="Delete Match">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
