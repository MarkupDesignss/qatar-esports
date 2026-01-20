@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3 style="font-size: 1.7rem;font-weight:600;">Our services</h3>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary mb-3">Add Service</a>
    </div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Image</th>
            <th>Title</th>
            <th>Status</th>
            <th>Order</th>
            <th width="150">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($services as $service)
        <tr>
            <td>
                @if($service->image)
                    <img src="{{ asset('storage/'.$service->image) }}" width="80">
                @endif
            </td>
            <td>{{ $service->title }}</td>
            <td>{{ $service->status ? 'Active' : 'Inactive' }}</td>
            <td>{{ $service->sort_order }}</td>
          <td>
            {{-- Edit --}}
            <a href="{{ route('admin.services.edit', $service) }}"
               class="btn btn-sm btn-warning"
               data-bs-toggle="tooltip"
               title="Edit Service">
                <i class="bi bi-pencil-square"></i>
            </a>
        
            {{-- Delete --}}
            <form method="POST"
                  action="{{ route('admin.services.destroy', $service) }}"
                  class="d-inline"
                  onsubmit="return confirm('Delete this service?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger"
                        data-bs-toggle="tooltip"
                        title="Delete Service">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </td>

        </tr>
        @endforeach
    </tbody>
</table>
@endsection
