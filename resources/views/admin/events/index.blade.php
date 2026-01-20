@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h4 style="font-size: 1.7rem;font-weight:600;">Featured Events</h4>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">Add Event</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Image</th>
                <th>Second Image</th>
                <th>Title</th>
                <th>Status</th>
                <th width="180">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
                <tr>
                    <td>
                        <img src="{{ asset('storage/'.$event->image) }}" width="80">
                    </td>
                    <td>
                        <img src="{{ asset('storage/'.$event->image_second) }}" width="80">
                    </td>
                    <td>{{ $event->title }}</td>
                    <td>
                        <form action="{{ route('admin.events.status', $event) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm {{ $event->status ? 'btn-success' : 'btn-secondary' }}">
                                {{ $event->status ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        {{-- Edit --}}
                        <a href="{{ route('admin.events.edit', $event) }}"
                           class="btn btn-sm btn-warning"
                           data-bs-toggle="tooltip"
                           title="Edit Event">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    
                        {{-- Delete --}}
                        <form action="{{ route('admin.events.destroy', $event) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    data-bs-toggle="tooltip"
                                    title="Delete Event"
                                    onclick="return confirm('Are you sure?')">
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
