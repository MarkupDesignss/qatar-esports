@extends('layouts.admin')


@section('content')
<div class="container">

    <div class="d-flex justify-content-between">

        <h2 style="font-size: 1.7rem;font-weight:600;">Challenges</h2>

        <a href="{{ route('admin.challenge.create') }}" class="btn btn-primary mb-3">
            Add Challenge
        </a>
    </div>


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Heading</th>
                <th>Image</th>
                <th>Video</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($challenges as $challenge)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $challenge->heading }}</td>
                    <td>
                        @if($challenge->image)
                            <img src="{{ asset('storage/'.$challenge->image) }}" width="80">
                        @endif
                    </td>
                    <td>
                        @if($challenge->video_url)
                            <a href="{{ $challenge->video_url }}" target="_blank">View</a>
                        @endif
                    </td>
                    <td>
                        {{-- Edit --}}
                        <a href="{{ route('admin.challenge.edit', $challenge->id) }}"
                           class="btn btn-sm btn-warning"
                           data-bs-toggle="tooltip"
                           title="Edit Challenge">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    
                        {{-- Delete --}}
                        <form action="{{ route('admin.challenge.destroy', $challenge->id) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    data-bs-toggle="tooltip"
                                    title="Delete Challenge"
                                    onclick="return confirm('Are you sure?')">
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
