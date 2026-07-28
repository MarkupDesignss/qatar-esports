@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">
    
    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
            <i class="bi bi-layout-text-window me-2"></i>Footer Settings
        </h4>
        @if (hasPermission('footer.update'))
        <a href="{{ route('admin.footer.edit') }}" class="btn btn-primary btn-sm w-sm-auto">
            <i class="bi bi-pencil-square me-1"></i> Edit Footer
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

   

    {{-- Footer Settings Details --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <tbody>
                        {{-- Tagline --}}
                        <tr>
                            <th width="200" class="bg-light text-nowrap" style="width: 25%;">
                                <i class="bi bi-quote me-2"></i>Tagline
                            </th>
                            <td class="small">
                                @if($settings->tagline ?? null)
                                    {{ $settings->tagline }}
                                @else
                                    <span class="text-muted fst-italic">N/A</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Description --}}
                        <tr>
                            <th class="bg-light text-nowrap" style="width: 25%;">
                                <i class="bi bi-text-paragraph me-2"></i>Description
                            </th>
                            <td class="small">
                                @if($settings->description ?? null)
                                    {{ strip_tags($settings->description) }}
                                @else
                                    <span class="text-muted fst-italic">N/A</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Copyright Text --}}
                        <tr>
                            <th class="bg-light text-nowrap" style="width: 25%;">
                                <i class="bi bi-c-circle me-2"></i>Copyright Text
                            </th>
                            <td class="small">
                                @if($settings->copyright_text ?? null)
                                    {{ $settings->copyright_text }}
                                @else
                                    <span class="text-muted fst-italic">N/A</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Social Media Section Header --}}
                        <tr class="table-secondary">
                            <th colspan="2" class="fw-bold">
                                <i class="bi bi-share-fill me-2"></i>Social Media Links
                            </th>
                        </tr>

                        {{-- Discord --}}
                        <tr>
                            <th class="bg-light text-nowrap" style="width: 25%;">
                                <i class="bi bi-discord me-2" style="color: #5865F2;"></i>Discord
                            </th>
                            <td class="small">
                                @if($settings->discord_url ?? null)
                                    <a href="{{ $settings->discord_url }}" target="_blank" class="text-decoration-none">
                                        {{ $settings->discord_url }}
                                        <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                    </a>
                                @else
                                    <span class="text-muted fst-italic">Not set</span>
                                @endif
                            </td>
                        </tr>

                        {{-- YouTube --}}
                        <tr>
                            <th class="bg-light text-nowrap" style="width: 25%;">
                                <i class="bi bi-youtube me-2" style="color: #FF0000;"></i>YouTube
                            </th>
                            <td class="small">
                                @if($settings->youtube_url ?? null)
                                    <a href="{{ $settings->youtube_url }}" target="_blank" class="text-decoration-none">
                                        {{ $settings->youtube_url }}
                                        <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                    </a>
                                @else
                                    <span class="text-muted fst-italic">Not set</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Instagram --}}
                        <tr>
                            <th class="bg-light text-nowrap" style="width: 25%;">
                                <i class="bi bi-instagram me-2" style="color: #E4405F;"></i>Instagram
                            </th>
                            <td class="small">
                                @if($settings->instagram_url ?? null)
                                    <a href="{{ $settings->instagram_url }}" target="_blank" class="text-decoration-none">
                                        {{ $settings->instagram_url }}
                                        <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                    </a>
                                @else
                                    <span class="text-muted fst-italic">Not set</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Twitter --}}
                        <tr>
                            <th class="bg-light text-nowrap" style="width: 25%;">
                                <i class="bi bi-twitter-x me-2"></i>Twitter / X
                            </th>
                            <td class="small">
                                @if($settings->twitter_url ?? null)
                                    <a href="{{ $settings->twitter_url }}" target="_blank" class="text-decoration-none">
                                        {{ $settings->twitter_url }}
                                        <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                    </a>
                                @else
                                    <span class="text-muted fst-italic">Not set</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Contact Information Section Header --}}
                        <tr class="table-secondary">
                            <th colspan="2" class="fw-bold">
                                <i class="bi bi-info-circle-fill me-2"></i>Contact Information
                            </th>
                        </tr>

                        {{-- Email --}}
                        <tr>
                            <th class="bg-light text-nowrap" style="width: 25%;">
                                <i class="bi bi-envelope me-2" style="color: #EA4335;"></i>Email
                            </th>
                            <td class="small">
                                @if($settings->email ?? null)
                                    <a href="mailto:{{ $settings->email }}" class="text-decoration-none">
                                        {{ $settings->email }}
                                        <i class="bi bi-envelope-paper ms-1 small"></i>
                                    </a>
                                @else
                                    <span class="text-muted fst-italic">Not set</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Phone --}}
                        <tr>
                            <th class="bg-light text-nowrap" style="width: 25%;">
                                <i class="bi bi-telephone me-2" style="color: #34A853;"></i>Phone
                            </th>
                            <td class="small">
                                @if($settings->contact_phone ?? null)
                                    <a href="tel:{{ $settings->contact_phone }}" class="text-decoration-none">
                                        {{ $settings->contact_phone }}
                                        <i class="bi bi-phone ms-1 small"></i>
                                    </a>
                                @else
                                    <span class="text-muted fst-italic">Not set</span>
                                @endif
                            </td>
                        </tr>

                        {{-- WhatsApp Number --}}
                        <tr>
                            <th class="bg-light text-nowrap" style="width: 25%;">
                                <i class="bi bi-whatsapp me-2" style="color: #25D366;"></i>WhatsApp
                            </th>
                            <td class="small">
                                @if($settings->whatsapp_number ?? null)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp_number) }}" 
                                       target="_blank" class="text-decoration-none">
                                        {{ $settings->whatsapp_number }}
                                        <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                    </a>
                                @else
                                    <span class="text-muted fst-italic">Not set</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Address --}}
                        <tr>
                            <th class="bg-light text-nowrap" style="width: 25%;">
                                <i class="bi bi-geo-alt me-2" style="color: #4285F4;"></i>Address
                            </th>
                            <td class="small">
                                @if($settings->contact_address ?? null)
                                    <div>{{ $settings->contact_address }}</div>
                                    @if(isset($settings->google_map_url))
                                        <a href="{{ $settings->google_map_url }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                            <i class="bi bi-map me-1"></i> View on Map
                                        </a>
                                    @endif
                                @else
                                    <span class="text-muted fst-italic">Not set</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    @if (hasPermission('footer.update'))
    <div class="mt-4 text-center">
        <a href="{{ route('admin.footer.edit') }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i> Edit Footer Settings
        </a>
    </div>
    @endif
</div>

{{-- Custom Styles for Better Mobile Display --}}
<style>
    @media (max-width: 576px) {
        .table th {
            font-size: 0.8rem;
            padding: 0.5rem;
        }
        .table td {
            font-size: 0.8rem;
            padding: 0.5rem;
            word-break: break-word;
        }
        .table .text-nowrap {
            white-space: normal !important;
            min-width: 100px;
        }
        .container-fluid {
            padding: 0.5rem !important;
        }
    }
    
    @media (max-width: 768px) {
        .table th {
            width: 30% !important;
            min-width: 80px;
        }
        .table td {
            width: 70% !important;
        }
    }
    
    /* Improve readability on very small screens */
    @media (max-width: 400px) {
        .table th {
            font-size: 0.7rem;
            padding: 0.4rem;
            min-width: 70px;
        }
        .table td {
            font-size: 0.7rem;
            padding: 0.4rem;
        }
        .btn-sm {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
    }
    
    .table-secondary th {
        background-color: #e9ecef !important;
    }
    
    /* Truncate long URLs on mobile */
    @media (max-width: 576px) {
        .table td a {
            display: inline-block;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    }
</style>
@endsection