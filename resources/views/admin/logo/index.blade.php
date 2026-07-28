@extends('layouts.admin')

@section('content')
<div class="container-fluid">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">Logo Management</h4>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th width="120">Logo</th>
                        <th>Title</th>
                          @if (hasPermission('logo.update'))
                        <th width="150">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if($logo)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . $logo->image) }}"
                                     style="height:60px">
                            </td>
                            <td>{{ $logo->title }}</td>
                            @if (hasPermission('logo.update'))
                           <td>
                            <a href="{{ route('admin.logo.edit') }}"
                               class="btn btn-sm btn-primary"
                               data-bs-toggle="tooltip"
                               title="Edit Logo">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>
                        @endif

                        </tr>
                    @else
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                No logo found
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
