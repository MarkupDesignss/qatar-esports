@extends('layouts.admin')

@section('content')
<div class="container">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <div class="d-flex justify-content-between mb-3">
        <h1 style="font-size: 1.7rem;font-weight:600;">Live Streams</h1>
        <a href="{{ route('admin.livestream.create') }}" class="btn btn-primary mb-3">Add New Live Stream</a>

    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Game</th>
                <th>Tournament</th>
                <th>Channel</th>
                <th>Platform</th>
                <th>Language</th>
                <th>Is Live</th>
                <!--<th>Viewer Count</th>-->
                <th>Video URL</th> {{-- New column --}}
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($liveStreams as $stream)
                <tr>
                    <td>{{ $stream->id }}</td>
                    <td>{{ $stream->game->name ?? '-' }}</td>
                    <td>{{ $stream->tournament->title ?? '-' }}</td>
                    <td>{{ $stream->channel_name }}</td>
                    <td>{{ $stream->platform }}</td>
                    <td>{{ $stream->language }}</td>
                    <td>{{ $stream->is_live ? 'Yes' : 'No' }}</td>
                     <!--<td>{{ $stream->viewer_count ?? 0 }}</td>-->
                    <td>
                        @if($stream->video_url)
                            <a href="{{ $stream->video_url }}" target="_blank" class="btn btn-sm btn-primary">
                                Watch Video
                            </a>
                        @else
                            -
                        @endif
                    </td>
                   <td>
    {{-- Edit Icon --}}
    <a href="{{ route('admin.livestream.edit', $stream->id) }}" 
       class="btn btn-sm btn-warning" 
       data-bs-toggle="tooltip" 
       title="Edit Live Stream">
        <i class="bi bi-pencil-square"></i>
    </a>

    {{-- Delete Icon --}}
    <form action="{{ route('admin.livestream.destroy', $stream->id) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" 
                onclick="return confirm('Are you sure?')" 
                data-bs-toggle="tooltip" 
                title="Delete Live Stream">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</td>

                </tr>
            @endforeach
        </tbody>
    </table>


    {{ $liveStreams->links() }}
</div>
@endsection
