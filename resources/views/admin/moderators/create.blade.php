@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-2 px-sm-3 px-md-4">

        <div class="card shadow">

            <div class="card-header bg-white py-3">
                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
                    <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                        <i class="bi bi-person-plus me-2"></i>Add Moderator
                    </h4>
                    <a href="{{ route('admin.moderators.index') }}" class="btn btn-secondary btn-sm btn-md-normal  w-sm-auto">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <div class="card-body p-3 p-sm-4">

                <form action="{{ route('admin.moderators.store') }}" method="POST">

                    @csrf

                    <div class="row g-3">

                        <div class="col-12">

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Full Name <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="name" 
                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
                                    placeholder="Enter full name">

                                @error('name')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        <div class="col-12">

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Email Address <span class="text-danger">*</span>
                                </label>

                                <input type="email" name="email" 
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="Enter email address">

                                @error('email')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        <div class="col-12 col-md-6">

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Password <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">
                                    <input type="password" name="password" 
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        id="password"
                                        placeholder="Enter password">
                                    <button class="btn btn-outline-secondary" type="button" 
                                        onclick="togglePassword('password')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>

                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        <div class="col-12 col-md-6">

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Confirm Password <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">
                                    <input type="password" name="password_confirmation" 
                                        class="form-control form-control-lg"
                                        id="password_confirmation"
                                        placeholder="Confirm password">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password_confirmation')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>

                            </div>

                        </div>

                        <div class="col-12">

                            <div class="d-flex flex-column flex-sm-row gap-2 gap-sm-3 mt-2">
                                <button class="btn btn-primary btn-sm w-sm-auto px-4">
                                    <i class="bi bi-check-circle"></i> Save Moderator
                                </button>
                                <a href="{{ route('admin.moderators.index') }}" class="btn btn-secondary btn-sm w-sm-auto px-4">
                                    Cancel
                                </a>
                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
@endsection