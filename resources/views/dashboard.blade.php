<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->isAdmin() ? __('Admin Dashboard') : __('Client Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (Auth::user()->isAdmin())
                        <!-- Admin Dashboard Content -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium">Welcome, {{ Auth::user()->name }}!</h3>
                            <p class="mt-2 text-gray-600">
                                You are logged in as an administrator. Here you can manage all system resources.
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-medium text-blue-900">Clients</h4>
                                <p class="text-2xl font-bold mt-2">{{ $clientCount ?? 0 }}</p>
                            </div>
                            
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="font-medium text-green-900">Couples</h4>
                                <p class="text-2xl font-bold mt-2">{{ $coupleCount ?? 0 }}</p>
                            </div>
                            
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <h4 class="font-medium text-yellow-900">Events</h4>
                                <p class="text-2xl font-bold mt-2">{{ $eventCount ?? 0 }}</p>
                            </div>
                            
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <h4 class="font-medium text-purple-900">Guests</h4>
                                <p class="text-2xl font-bold mt-2">{{ $guestCount ?? 0 }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900">Recent Clients</h4>
                                <ul class="mt-2 divide-y">
                                    @if(isset($recentClients) && $recentClients->count() > 0)
                                        @foreach($recentClients as $client)
                                            <li class="py-2">
                                                <p class="font-medium">{{ $client->client_name }}</p>
                                                <p class="text-sm text-gray-600">{{ $client->phone ?? 'No phone provided' }}</p>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="py-2 text-gray-500">No clients found</li>
                                    @endif
                                </ul>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900">Recent Events</h4>
                                <ul class="mt-2 divide-y">
                                    @if(isset($recentEvents) && $recentEvents->count() > 0)
                                        @foreach($recentEvents as $event)
                                            <li class="py-2">
                                                <p class="font-medium">{{ $event->event_name }}</p>
                                                <p class="text-sm text-gray-600">
                                                    {{ $event->couple->groom_name ?? '' }} & {{ $event->couple->bride_name ?? '' }}
                                                </p>
                                                <p class="text-sm text-gray-600">{{ $event->event_date->format('M d, Y') }}</p>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="py-2 text-gray-500">No events found</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @else
                        <!-- Client Dashboard Content -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium">Welcome, {{ Auth::user()->name }}!</h3>
                            <p class="mt-2 text-gray-600">
                                Here you can manage your wedding information and view related details.
                            </p>
                        </div>

                        @if(isset($client))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900">Your Profile</h4>
                                <div class="mt-2">
                                    <p><strong>Name:</strong> {{ $client->client_name }}</p>
                                    <p><strong>Address:</strong> {{ $client->address ?? 'Not provided' }}</p>
                                    <p><strong>Phone:</strong> {{ $client->phone ?? 'Not provided' }}</p>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900">Your Weddings</h4>
                                <div class="mt-2">
                                    @if(isset($couples) && $couples->count() > 0)
                                        <p>You have {{ $couples->count() }} wedding(s) registered.</p>
                                        <ul class="list-disc list-inside mt-2">
                                            @foreach($couples as $couple)
                                                <li>{{ $couple->groom_name }} & {{ $couple->bride_name }} - {{ $couple->wedding_date }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>You haven't registered any weddings yet.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Your client profile is not set up yet. Please contact support.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
