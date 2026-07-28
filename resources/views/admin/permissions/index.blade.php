@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-2 px-sm-3 px-md-4">

        <div class="card shadow">

            <div class="card-header bg-white py-3">
                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
                    <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                        <i class="bi bi-shield-lock me-2"></i>Moderator Permissions
                    </h4>
                </div>
            </div>

            <form action="{{ route('admin.permissions.update') }}" method="POST">

                @csrf

                <div class="card-body p-3 p-sm-4">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            <span class="d-block">{{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @foreach ($permissions as $module => $items)
                        <div class="card mb-4 shadow-sm">

                            <div class="card-header bg-light py-2 py-sm-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-folder me-2"></i>
                                    <strong class="text-uppercase small">{{ $module }}</strong>
                                    <span class="badge bg-secondary ms-2">{{ count($items) }}</span>
                                </div>
                            </div>

                            <div class="card-body p-2 p-sm-3">

                                <div class="row g-2 g-sm-3">

                                    @foreach ($items as $permission)
                                        <div class="col-6 col-sm-4 col-md-3 col-lg-3 mb-2 mb-sm-3">

                                            <div class="form-check">

                                                <input class="form-check-input" type="checkbox" 
                                                    name="permissions[]"
                                                    value="{{ $permission->id }}" 
                                                    id="permission{{ $permission->id }}"
                                                    {{ in_array($permission->id, $selected) ? 'checked' : '' }}>

                                                <label class="form-check-label" for="permission{{ $permission->id }}">
                                                    <span class="small">{{ $permission->name }}</span>
                                                </label>

                                            </div>

                                        </div>
                                    @endforeach

                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>

                @if (hasPermission('roles.edit'))
                    <div class="card-footer bg-white py-3">
                        <div class="d-flex flex-column flex-sm-row gap-2 gap-sm-3">
                            <button class="btn btn-primary btn-sm">
                                <i class="bi bi-check-circle"></i> 
                                <span>Save Permissions</span>
                            </button>
                            
                            <button type="button" class="btn btn-outline-secondary w-sm-auto" 
                                onclick="window.location.href='{{ route('admin.moderators.index') }}'">
                                <i class="bi bi-arrow-left"></i> 
                                <span>Back to Moderators</span>
                            </button>
                        </div>
                    </div>
                @endif

            </form>

        </div>

    </div>

    <script>
        // Select/Deselect All functionality (optional but useful)
        document.addEventListener('DOMContentLoaded', function() {
            // You can add a "Select All" toggle if needed
            // Example: Add select all buttons to each module card header
            const cards = document.querySelectorAll('.card.mb-4');
            cards.forEach(card => {
                const header = card.querySelector('.card-header');
                const checkboxes = card.querySelectorAll('input[type="checkbox"]');
                
                // Add Select All checkbox to header
                const selectAllDiv = document.createElement('div');
                selectAllDiv.className = 'ms-auto d-flex align-items-center';
                selectAllDiv.innerHTML = `
                    <div class="form-check form-check-inline me-2">
                        <input class="form-check-input" type="checkbox" id="selectAll-${Math.random().toString(36).substr(2, 9)}">
                        <label class="form-check-label small">Select All</label>
                    </div>
                `;
                
                // Append to header if needed
                // header.appendChild(selectAllDiv);
                
                // Select All functionality
                const selectAllCheckbox = selectAllDiv.querySelector('input[type="checkbox"]');
                if (selectAllCheckbox) {
                    selectAllCheckbox.addEventListener('change', function() {
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                    });
                }
            });
        });
    </script>
@endsection