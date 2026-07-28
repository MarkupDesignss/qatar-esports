@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')

<style>
    .password-toggle {
        position: absolute;
        right: 10px;
        top: 70%;
        transform: translateY(-50%);
        background: #f3f4f6;         /* light grey background to make it visible */
        border: 1px solid #d1d5db;
        border-radius: 4px;
        cursor: pointer;
        padding: 4px 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        line-height: 1;
    }
    .password-toggle:hover {
        background: #e5e7eb;
    }
    .password-toggle svg {
        width: 18px;
        height: 18px;
        display: block;
        fill: none;
        stroke: #374151;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .position-relative {
        position: relative;
    }
</style>

<div class="bg-white p-8 rounded shadow w-full max-w-md">

    <h2 class="text-xl font-bold mb-4 text-center">Reset Password</h2>

    @if ($errors->any())
        <div class="text-red-500 mb-3 text-center">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.reset.password') }}">
        @csrf

        {{-- New Password with toggle --}}
        <div class="mb-3 position-relative">
            <label for="password" class="form-label">New Password</label>
            <input type="password" name="password" id="password" required 
                   class="w-full border p-2 pr-12 rounded">
            <button type="button" class="password-toggle" data-target="password" aria-label="Toggle password visibility">
                {{-- Eye icon (open) --}}
                <svg viewBox="0 0 24 24" class="eye-icon">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
            </button>
        </div>

        {{-- Confirm Password with toggle --}}
        <div class="mb-4 position-relative">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required 
                   class="w-full border p-2 pr-12 rounded">
            <button type="button" class="password-toggle" data-target="password_confirmation" aria-label="Toggle password visibility">
                <svg viewBox="0 0 24 24" class="eye-icon">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
            </button>
        </div>

        <button class="w-full bg-teal-500 text-white py-2 rounded hover:bg-teal-600 transition">
            Reset Password
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.password-toggle').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (!input) return;

                const svg = this.querySelector('svg');
                const isPassword = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPassword ? 'text' : 'password');

                // Toggle SVG: show eye or eye-slash
                if (isPassword) {
                    // Show eye-slash (hidden state)
                    svg.innerHTML = `
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    `;
                } else {
                    // Show eye (visible state)
                    svg.innerHTML = `
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    `;
                }
            });
        });
    });
</script>

@endsection