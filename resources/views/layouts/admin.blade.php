<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qatar Esports Admin - @yield('title')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('images/fav-icon.png') }}">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons (optional but recommended for icons) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Font Awesome (for the icons used in the design) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #818cf8;
            --primary-dark: #3730a3;
            --secondary: #10b981;
            --accent: #f59e0b;
            --light-bg: #f8fafc;
            --dark-bg: #1e293b;
            --card-bg: #ffffff;
            --sidebar-width: 250px;
            --header-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--light-bg);
            color: #334155;
            overflow-x: hidden;
        }

        /* Header */
        .main-header {
            background: white;
            height: var(--header-height);
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.2);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-container img {
            height: 50px;
            width: auto;
            object-fit: contain;
            display: block;
            max-width: 100%;
        }

        .logo {
            width: 30px;
            height: 30px;
            border-radius: 10px;
            background: linear-gradient(45deg, var(--primary-light), #ffffff);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-dark);
            font-weight: 500;
            font-size: 1.1rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .brand-text {
            font-size: 1.4rem;
            font-weight: 700;
            color: white;
            letter-spacing: -0.5px;
        }

        /* Menu Toggle Button */
        .menu-toggle {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 101;
        }

        .menu-toggle:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }

        .menu-toggle span {
            display: block;
            width: 22px;
            height: 2px;
            background: black;
            margin: 3px 0;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .menu-toggle.active span:nth-child(1) {
            transform: translateY(8px) rotate(45deg);
        }

        .menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .menu-toggle.active span:nth-child(3) {
            transform: translateY(-8px) rotate(-45deg);
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--secondary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .user-role {
            font-size: 0.8rem;
        }

        /* Sidebar */
        .sidebar {
            background: var(--card-bg);
            width: var(--sidebar-width);
            position: fixed;
            top: var(--header-height);
            left: 0;
            bottom: 0;
            z-index: 90;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.35s cubic-bezier(.2, .9, .3, 1), width 0.35s cubic-bezier(.2, .9, .3, 1);
            padding: 1.5rem 0;
            overflow-y: auto;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            padding: 0 0.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.4rem 0.7rem;
            border-radius: 10px;
            color: #64748b;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-item:hover {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.1), transparent);
            color: var(--primary);
            transform: translateX(5px);
        }

        .nav-item.active {
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            color: white;
        }

        .nav-icon {
            margin-right: 0.75rem;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .nav-label {
            font-size: 0.95rem;
        }

        .nav-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 1rem 0;
        }

        .nav-section {
            font-size: 0.75rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0 1rem;
            margin: 1.5rem 0 0.75rem 0;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 1rem;
            min-height: calc(100vh - var(--header-height));
            transition: margin-left 0.35s cubic-bezier(.2, .9, .3, 1), width 0.35s cubic-bezier(.2, .9, .3, 1);
            width: calc(100% - var(--sidebar-width));
        }

        .content-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            border: 1px solid #f1f5f9;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-title-icon {
            background: linear-gradient(45deg, var(--primary-light), var(--primary));
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #f1f5f9;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-dark);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        .stat-change {
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 0.25rem;
        }

        .stat-change.positive {
            color: var(--secondary);
        }

        .stat-change.negative {
            color: #ef4444;
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary), var(--primary-light));
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(45deg, var(--secondary), #34d399);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }
        
        /*CSS Fixes*/
        
        .input-group button.btn.btn-outline-secondary {
            padding: 0.75rem 1rem;
            background: #eee;
        }
        
        .input-group button.btn.btn-outline-secondary:hover {
            color: #000;
        }
        
        table.table.table-hover.table-bordered.align-middle.mb-0 {
            min-width: max-content;
        }
        
        .d-flex.flex-column.flex-sm-row.justify-content-between.align-items-center.gap-2 small {
            width: 100% !important;
        }
        
        button.btn.btn-outline-secondary.btn-sm {
            background: #f0f0f0;
        }
        
        button.btn.btn-outline-secondary.btn-sm:hover {
            background: #0f0f0f;
        }

        /* Mobile Responsiveness */
        /* Tablet Responsiveness */
        @media (min-width: 769px) and (max-width: 992px) {
            .logo-container img {
                height: 38px;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }

            .brand-text {
                display: none;
            }
        }

        @media (min-width: 992px) {
            .sidebar.active {
                transform: translateX(-100%);
                width: 0;
            }

            .main-content.collapsed {
                margin-left: 0;
                width: 100%;
            }
        }
        
        table.table.table-bordered.align-middle.mb-0 {
            min-width: max-content;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .logo-container img {
                height: 32px;
            }

            .main-content {
                padding: 1.5rem 1rem;
            }

            .content-card {
                padding: 1rem 0.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .main-header {
                padding: 0 1rem;
            }
            
            .header-left {
                justify-content: start;
                gap: 5px;
            }
        }

        @media (min-width: 993px) {
            .sidebar.hidden {
                transform: translateX(-100%);
            }

            .main-content.full-width {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 89;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }

        .has-submenu .submenu {
            display: none;
            padding-left: 15px;
        }

        .has-submenu.open .submenu {
            display: block;
        }

        .submenu-item {
            display: block;
            padding: 8px 12px;
            font-size: 14px;
        }

        .submenu-item.active {
            font-weight: 600;
            color: #0d6efd;
        }

        .submenu-arrow {
            margin-left: auto;
            font-size: 12px;
        }

        .sidebar-item>.sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            font-weight: 500;
            cursor: pointer;
            color: #64748b;
        }

        .sidebar-item .arrow {
            transition: transform 0.3s ease;
        }

        .sidebar-item.open .arrow {
            transform: rotate(180deg);
        }

        .submenu {
            display: none;
            padding-left: 40px;
            margin-top: 5px;
        }

        .sidebar-item.open .submenu {
            display: block;
        }

        .submenu li {
            color: #6b7280;
        }

        .submenu li a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            font-size: 14px;
            font-weight: 600;
            color: #484b4e;
            transition: all 0.2s ease;
        }

        .submenu li a:hover {
            color: #0d6efd;
        }

        .submenu li a.active {
            color: #0d6efd;
            font-weight: 600;
        }

        .dropdown-sidebar {
            display: flex;
            flex-direction: column;
            align-items: start;
        }

        .dropdown-sidebar .dropdown-toggle {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .dropdown-menu-sidebar {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease, opacity 0.25s ease;
            opacity: 0;
            padding-left: 35px;
        }

        .dropdown-sidebar.open .dropdown-menu-sidebar {
            max-height: 500px;
            opacity: 1;
        }

        .dropdown-item {
            padding: 8px 0;
            font-size: 14px;
            color: #64748b;
        }

        .dropdown-item.active {
            color: #64748b;
            font-weight: 600;
        }

        .admin-logo {
            height: 50px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        /* Password toggle button - fixed vertical alignment */
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
            z-index: 5;
        }
        .password-toggle:hover {
            color: #374151;
        }
        .password-toggle:focus {
            outline: none;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="main-header">
        <div class="header-left">
            <button class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="logo-container">
                <img src="{{ asset('storage/' . \App\Models\Logo::first()->image) }}">
            </div>
        </div>

        <div class="user-info" style=" margin-left:5px;">
            <div class="user-details">
                <div class="user-name">
                    {{ Auth::guard('admin')->user()->name ?? 'Admin User' }}
                </div>

                <div class="user-role">
                    {{ (Auth::guard('admin')->user()->role ?? '') === 'admin' ? 'Administrator' : 'Moderator' }}
                </div>
            </div>

            <div class="user-avatar" id="userAvatar" style="cursor: pointer;">
                {{ substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1) }}
            </div>
        </div>
    </header>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="nav-icon bi bi-speedometer2"></i>
                <span class="nav-label">Dashboard</span>
            </a>
            @if (hasPermission('users.view'))
                <a href="{{ route('admin.moderators.index') }}"
                    class="nav-item {{ request()->routeIs('admin.moderators.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-person-gear"></i>
                    <span class="nav-label">Moderators</span>
                </a>
            @endif
            @if (hasPermission('roles.view'))
                <a href="{{ route('admin.permissions.index') }}"
                    class="nav-item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-shield-lock"></i>
                    <span class="nav-label">Permissions</span>
                </a>
            @endif
            @if (hasPermission('users.view'))
                <a href="{{ route('admin.user.index') }}"
                    class="nav-item {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-person-circle"></i>
                    <span class="nav-label">Users</span>
                </a>
            @endif

            @if (hasPermission('games.view'))
                <a href="{{ route('admin.games.index') }}"
                    class="nav-item {{ request()->routeIs('admin.games.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-controller"></i>
                    <span class="nav-label">Games</span>
                </a>
            @endif
            @if (hasPermission('maps.view'))
                <a href="{{ route('admin.maps.index') }}"
                    class="nav-item {{ request()->routeIs('admin.maps.*') ? 'active' : '' }}"
                    style="display:flex;gap:12px;">
                    <i class="fa fa-globe"></i>
                    <span class="nav-label">Maps</span>
                </a>
            @endif
            @if (hasPermission('tournament.view'))
                <a href="{{ route('admin.tournaments.index') }}"
                    class="nav-item {{ request()->routeIs('admin.tournaments.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-trophy"></i>
                    <span class="nav-label">Tournaments</span>
                </a>
            @endif
            @if (hasPermission('participants.view'))
                <div
                    class="nav-item dropdown-sidebar {{ request()->is('admin/tournament-registrations/solo*', 'admin/tournament-registrations/team*') ? 'open' : '' }}">
                    <a href="javascript:void(0)" class="nav-link dropdown-toggle">
                        <i class="nav-icon bi bi-people"></i>
                        <span class="nav-label">Participants</span>
                    </a>
                    <div class="dropdown-menu-sidebar">
                        <a href="{{ route('admin.tournament-registrations.solo') }}"
                            class="dropdown-item {{ request()->is('admin/tournament-registrations/solo*') ? 'active' : '' }}">
                            Solo List
                        </a>
                        <a href="{{ route('admin.tournament-registrations.team') }}"
                            class="dropdown-item {{ request()->is('admin/tournament-registrations/team*') ? 'active' : '' }}">
                            Team List
                        </a>
                    </div>
                </div>
            @endif
            @if (hasPermission('livestream.view'))
                <a href="{{ route('admin.livestream.index') }}"
                    class="nav-item {{ request()->routeIs('admin.livestream.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-camera-video"></i>
                    <span class="nav-label">Live Stream</span>
                </a>
            @endif
            @if (hasPermission('contact.view'))
                <a href="{{ route('admin.contact.index') }}"
                    class="nav-item {{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-chat-dots"></i>
                    <span class="nav-label">Contact Leads</span>
                </a>
            @endif
            @if (hasPermission('logo.view'))
                <a href="{{ route('admin.logo.index') }}"
                    class="nav-item {{ request()->routeIs('admin.logo.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-image"></i>
                    <span class="nav-label">Logo</span>
                </a>
            @endif
            @if (hasPermission('banner.view'))
                <a href="{{ route('admin.banners.index') }}"
                    class="nav-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-images"></i>
                    <span class="nav-label">Banners</span>
                </a>
            @endif
            <div
                class="nav-item dropdown-sidebar {{ request()->is('admin/partners*', 'admin/events*', 'admin/challenge*', 'admin/matches*', 'admin/previous-works*', 'admin/services*', 'admin/news*', 'admin/about*', 'admin/dashboard-image*') ? 'open' : '' }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle">
                    <i class="nav-icon bi bi-layout-text-window"></i>
                    <span class="nav-label">Content Management</span>
                </a>
                <div class="dropdown-menu-sidebar">
                    <a href="{{ route('admin.partners.index') }}"
                        class=" dropdown-item {{ request()->is('admin/partners*') ? 'active' : '' }}">
                        Partners
                    </a>
                    <a href="{{ route('admin.dashboard-images.index') }}"
                        class="dropdown-item {{ request()->is('admin/dashboard-images*') ? 'active' : '' }}">
                        Dashboard Images
                    </a>
                    <a href="{{ route('admin.challenge.index') }}"
                        class="dropdown-item {{ request()->is('admin/challenge*') ? 'active' : '' }}">
                        Challenge Section
                    </a>
                    <a href="{{ route('admin.matches.index') }}"
                        class="dropdown-item {{ request()->is('admin/matches*') ? 'active' : '' }}">
                        Match Highlights
                    </a>
                    <a href="{{ route('admin.previous-works.index') }}"
                        class="dropdown-item {{ request()->is('admin/previous-works*') ? 'active' : '' }}">
                        Previous work
                    </a>
                    <a href="{{ route('admin.services.index') }}"
                        class="dropdown-item {{ request()->is('admin/services*') ? 'active' : '' }}">
                        Our services
                    </a>
                    <a href="{{ route('admin.news-types.index') }}"
                        class="dropdown-item {{ request()->routeIs('admin.news-types.*') ? 'active' : '' }}">
                        News Types
                    </a>
                    <a href="{{ route('admin.news.index') }}"
                        class="dropdown-item {{ request()->is('admin/news*') ? 'active' : '' }}">
                        News
                    </a>
                    <a href="{{ route('admin.about.index') }}"
                        class="dropdown-item {{ request()->is('admin/about*') ? 'active' : '' }}">
                        About Us
                    </a>
                    <a href="{{ route('admin.contact-settings.index') }}"
                        class="dropdown-item {{ request()->is('admin/contact-settings*') ? 'active' : '' }}">
                        Contact Settings
                    </a>
                    <a href="{{ route('admin.pages.index') }}"
                        class="dropdown-item {{ request()->is('admin/pages*') ? 'active' : '' }}">
                        Legal Pages
                    </a>
                    <a href="{{ route('admin.footer.index') }}"
                        class="dropdown-item {{ request()->is('admin/footer*') ? 'active' : '' }}">
                        Footer Settings
                    </a>
                </div>
            </div>

            <div class="nav-divider"></div>
            <form method="POST" action="{{ route('admin.logout') }}" class="nav-item">
                @csrf
                <button type="submit" class="btn btn-link nav-link p-0 m-0">
                    <i class="nav-icon bi bi-box-arrow-right"></i>
                    <span class="nav-label">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <div class="content-card fade-in">
            <div class="fade-in" style="animation-delay: 0.5s">
                @yield('content')
            </div>
        </div>
    </main>

    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="h3 fw-bold text-gray-800 mb-2" id="profileModalLabel">Update Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                Please correct the errors below.
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="profileName" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="profileName" name="name"
                                value="{{ old('name', Auth::guard('admin')->user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="profileEmail" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="profileEmail" name="email"
                                value="{{ old('email', Auth::guard('admin')->user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password with toggle -->
                        <div class="mb-3 position-relative">
                            <label for="profilePassword" class="form-label">New Password (leave blank to keep current)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="profilePassword" name="password" autocomplete="new-password">
                            <button type="button" class="password-toggle" data-target="profilePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password with toggle -->
                        <div class="mb-3 position-relative">
                            <label for="profilePasswordConfirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="profilePasswordConfirmation"
                                   name="password_confirmation" autocomplete="new-password">
                            <button type="button" class="password-toggle" data-target="profilePasswordConfirmation">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        // -------- Sidebar Toggle --------
        document.getElementById('menuToggle').addEventListener('click', function() {
            this.classList.toggle('active');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');

            if (window.innerWidth < 993) {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : 'auto';
            } else {
                sidebar.classList.toggle('hidden');
                mainContent.classList.toggle('full-width');
            }
        });

        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            if (window.innerWidth < 993) {
                document.getElementById('menuToggle').classList.remove('active');
                document.getElementById('sidebar').classList.remove('active');
                this.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });

        window.addEventListener('resize', function() {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');

            if (window.innerWidth >= 993) {
                overlay.classList.remove('active');
                document.body.style.overflow = 'auto';
                if (sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    menuToggle.classList.remove('active');
                }
            } else {
                if (sidebar.classList.contains('hidden')) {
                    sidebar.classList.remove('hidden');
                    mainContent.classList.remove('full-width');
                }
            }
        });

        // -------- Dropdown Sidebar (Content Management) --------
        document.querySelectorAll('.dropdown-toggle').forEach(item => {
            item.addEventListener('click', function() {
                this.closest('.dropdown-sidebar').classList.toggle('open');
            });
        });

        // -------- Profile Modal Trigger --------
        document.addEventListener('DOMContentLoaded', function() {
            var avatar = document.getElementById('userAvatar');
            if (avatar) {
                avatar.addEventListener('click', function() {
                    var modalEl = document.getElementById('profileModal');
                    var modal = new bootstrap.Modal(modalEl);
                    modal.show();
                });
            }

            @if (session('show_profile_modal'))
                var modalEl = document.getElementById('profileModal');
                var modal = new bootstrap.Modal(modalEl);
                modal.show();
            @endif
        });

        // -------- PASSWORD TOGGLE (works for any .password-toggle) --------
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.password-toggle').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    if (!input) return;

                    const icon = this.querySelector('i');
                    const currentType = input.getAttribute('type');
                    const newType = currentType === 'password' ? 'text' : 'password';
                    input.setAttribute('type', newType);

                    if (newType === 'text') {
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                });
            });
        });
    </script>
</body>

</html>
