@extends('layouts.auth')

@section('title', 'Admin Login')

@section('styles')
<style>
.logo {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(45deg, var(--primary-light), #ffffff);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-dark);
    font-weight: 700;
    font-size: 1.2rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.8);
    margin: 0 auto;
}

.password-container {
    position: relative;
}

.password-container input {
    padding-right: 40px;
}

.password-toggle {
    position: absolute;
    right: 10px;
    top: 70%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #6b7280;
    font-size: 1.2rem;
    padding: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.password-toggle:hover {
    color: #374151;
}

.password-toggle:focus {
    outline: none;
}
.password-container {
    position: relative;
    width: 100%;
}

.password-container input {
    width: 100%;
    padding-right: 45px;
}

.password-toggle {
    position: absolute;
    top: 50%;
    right: 12px;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    padding: 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
    z-index: 10;
}

.password-toggle svg {
    width: 20px;
    height: 20px;
}

.password-toggle:hover {
    color: #111827;
}

.password-toggle:focus {
    outline: none;
}
</style>
@endsection

@section('content')
<div class="login-form bg-white p-8 rounded-lg shadow-lg w-full max-w-md">

    <div class="header-left">
        <div class="logo-container" style="display:flex;justify-content:center">
            <img src="{{ asset('storage/' . \App\Models\Logo::first()->image) }}">
        </div>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
    <div class="text-green-600 mb-4 text-center font-medium">
        {{ session('success') }}
    </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if ($errors->any())
    <div class="text-red-500 mb-4 text-center">
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <div class="space-y-4">

            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-teal-500"
                    required>
            </div>

            <div class="relative">
                <label class="block text-gray-700">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="w-full px-3 py-2 pr-10 border rounded focus:outline-none focus:ring-2 focus:ring-teal-500"
                    required
                >
            
                <button
                    type="button"
                    id="togglePassword"
                    style="margin-top:24px;"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
                    <svg id="eyeIcon"
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </button>
            </div>

            <button type="submit" class="w-full bg-teal-500 text-white py-2 rounded hover:bg-teal-600 transition">
                Login
            </button>

        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('admin.forgot-password.form') }}"
                class="text-sm text-teal-600 hover:text-teal-800 font-medium">
                Forgot your password?
            </a>
        </div>
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle the eye icon
        const svg = this.querySelector('svg');
        if (type === 'text') {
            // Eye with slash (hidden state)
            svg.innerHTML = `
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
            `;
        } else {
            // Eye open (visible state)
            svg.innerHTML = `
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            `;
        }
    });
});
</script>

@endsection