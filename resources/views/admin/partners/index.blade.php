@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between">

        <h2 style="font-size: 1.7rem;font-weight:600;">Partners</h2>

        <a href="{{ route('admin.partners.create') }}" class="btn btn-primary mb-3">Add Partner</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Logo</th>
                <th>Name</th>
                <th>Sort Order</th>
                <th>Status</th>
                <th width="150">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($partners as $partner)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="{{ asset('storage/'.$partner->logo) }}" width="60">
                    </td>
                    <td>{{ $partner->name }}</td>
                    <td>{{ $partner->sort_order }}</td>
                    <td>
                        <span class="badge {{ $partner->status ? 'bg-success' : 'bg-danger' }}">
                            {{ $partner->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                   <td>
                        {{-- Edit --}}
                        <a href="{{ route('admin.partners.edit', $partner) }}"
                           class="btn btn-sm btn-warning"
                           data-bs-toggle="tooltip"
                           title="Edit Partner">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    
                        {{-- Delete --}}
                        <form action="{{ route('admin.partners.destroy', $partner) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    data-bs-toggle="tooltip"
                                    title="Delete Partner"
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
