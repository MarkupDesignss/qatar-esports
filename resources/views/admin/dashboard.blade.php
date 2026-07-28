@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="space-y-8">

        <h2 class="text-3xl font-bold bg-gradient-to-r from-teal-500 to-blue-500 bg-clip-text text-transparent">
            Dashboard Overview
        </h2>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Active Users --}}
            <a href="{{ route('admin.user.index') }}"
                class="relative bg-gradient-to-br from-green-500 to-emerald-500 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>

                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Active Users</h3>
                    <i class="bi bi-person-check text-white text-xl"></i>
                </div>

                <p class="text-4xl font-bold mt-4 text-white">{{ $activeUsers ?? 0 }}</p>
                <p class="text-sm text-white/80 mt-2">Currently active users</p>
            </a>

            {{-- Total Games --}}
            <a href="{{ route('admin.games.index') }}"
                class="relative bg-gradient-to-br from-gray-500 to-slate-600 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>

                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Total Games</h3>
                    <i class="bi bi-controller text-white text-xl"></i>
                </div>

                <p class="text-4xl font-bold mt-4 text-white">{{ $games ?? 0 }}</p>
                <p class="text-sm text-white/80 mt-2">Total Games</p>
            </a>

            {{-- Tournaments --}}
            <a href="{{route('admin.tournaments.index')}}"
                class="relative bg-gradient-to-br from-blue-500 to-indigo-500 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>

                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Tournaments</h3>
                    <i class="bi bi-trophy text-white text-xl"></i>
                </div>

                <p class="text-4xl font-bold mt-4 text-white">{{ $tournaments ?? 0 }}</p>
                <p class="text-sm text-white/80 mt-2">Total tournaments</p>
            </a>
        </div>

        {{-- Quick Actions Section --}}
        
        <div class="mt-12">
          @if (hasPermission('tournament.add') || hasPermission('livestream.create') || hasPermission('games.create'))
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-3xl font-bold bg-gradient-to-r from-teal-500 to-blue-500 bg-clip-text text-transparent">
                        Quick Actions
                    </h3>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Create Tournament --}}
                @if (hasPermission('tournament.create'))
                <a href="{{ route('admin.tournaments.create') }}"
                    class="group block bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500">
                    
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="bi bi-trophy text-xl text-white"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-lg">Create Tournament</h4>
                                <p class="text-blue-100 text-sm">Add new tournament</p>
                            </div>
                        </div>
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 transition-colors">
                            <i class="bi bi-plus-lg text-white text-sm"></i>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            
                            <span class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-all">
                                Get Started
                                <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                            </span>
                        </div>
                    </div>
                </a>
                @endif

                {{-- Create Game --}}
                @if (hasPermission('games.create'))
                <a href="{{ route('admin.games.create') }}"
                    class="group block bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-emerald-500 dark:hover:border-emerald-500">
                    
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="bi bi-controller text-xl text-white"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-lg">Create Game</h4>
                                <p class="text-emerald-100 text-sm">Add new game</p>
                            </div>
                        </div>
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 transition-colors">
                            <i class="bi bi-plus-lg text-white text-sm"></i>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                           
                            <span class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 dark:text-emerald-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition-all">
                                Get Started
                                <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                            </span>
                        </div>
                    </div>
                </a>
                @endif

                {{-- Create Livestream --}}
                @if (hasPermission('livestream.create'))
                <a href="{{ route('admin.livestream.create') }}"
                    class="group block bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-red-500 dark:hover:border-red-500">
                    
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-red-500 to-pink-600 px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="bi bi-broadcast text-xl text-white"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-lg">Create Livestream</h4>
                                <p class="text-red-100 text-sm">Start new stream</p>
                            </div>
                        </div>
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 transition-colors">
                            <i class="bi bi-plus-lg text-white text-sm"></i>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            
                            <span class="inline-flex items-center gap-2 text-sm font-semibold text-red-600 dark:text-red-400 group-hover:text-red-700 dark:group-hover:text-red-300 transition-all">
                                Get Started
                                <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                            </span>
                        </div>
                    </div>
                </a>
                @endif

            </div>

            {{-- If no permissions --}}
            @if(!hasPermission('tournament.view') && !hasPermission('games.view') && !hasPermission('livestream.view'))
                <div class="text-center py-12 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600">
                    <i class="bi bi-lock text-4xl text-gray-400 dark:text-gray-500 mb-3 block"></i>
                    <p class="text-gray-500 dark:text-gray-400">You don't have permission to create any content.</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Contact your administrator for access.</p>
                </div>
            @endif
        </div>

    </div>
@endsection