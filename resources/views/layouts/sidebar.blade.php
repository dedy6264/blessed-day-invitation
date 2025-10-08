<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="mx-3 sidebar-brand-text">{{ config('app.name', 'Laravel') }}</div>
    </a>

    <!-- Divider -->
    <hr class="my-0 sidebar-divider">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('admin.dashboard') || request()->routeIs('client.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('client.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Management
    </div>

    <!-- Nav Item - Users -->
    @if(auth()->user()->role=="admin")
    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            <i class="fas fa-fw fa-users-cog"></i>
            <span>Users</span>
        </a>
    </li>
    
    <!-- Nav Item - Clients -->
    <li class="nav-item {{ request()->routeIs('clients.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('clients.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Clients</span>
        </a>
    </li>
    
    <!-- Nav Item - Attendant -->
    {{-- <li class="nav-item {{ request()->routeIs('invitation.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('invitation.present') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Attendant</span>
        </a>
    </li> --}}
    @else
     <!-- Nav Item - Attendant -->
    {{-- <li class="nav-item {{ request()->routeIs('my-invitation.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('my-invitation.present') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Attendant</span>
        </a>
    </li> --}}
    @endif
    
    <!-- Nav Item - Wedding Data Collapse Menu -->
    <li class="nav-item {{ request()->routeIs('couples.*') ||
     request()->routeIs('people.*') ||
     request()->routeIs('wedding-events.*') ||
     request()->routeIs('locations.*') ||
     request()->routeIs('gallery-images.*') ||
     request()->routeIs('timeline-events.*') ||
     request()->routeIs('gifts.*') ||
     request()->routeIs('guests.*') ||
     request()->routeIs('invitations.*') ||
     request()->routeIs('guest-messages.*') ||
     request()->routeIs('my-couples.*') ||
     request()->routeIs('my-wedding-events.*') ||
     request()->routeIs('my-gallery-images.*') ||
     request()->routeIs('my-timeline-events.*') ||
     request()->routeIs('my-gifts.*') ||
     request()->routeIs('my-guests.*') ||
     request()->routeIs('my-people.*') ||
     request()->routeIs('my-guest-attendants.*') ||
     request()->routeIs('my-locations.*') ||
     request()->routeIs('my-invitations.*') ||
     request()->routeIs('my-guest-messages.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('couples.*') ||
         request()->routeIs('people.*') ||
         request()->routeIs('wedding-events.*') ||
         request()->routeIs('locations.*') ||
         request()->routeIs('gallery-images.*') ||
         request()->routeIs('timeline-events.*') ||
         request()->routeIs('gifts.*') ||
         request()->routeIs('guests.*') ||
         request()->routeIs('invitations.*') ||
         request()->routeIs('guest-messages.*') ||
         request()->routeIs('my-couples.*') ||
         request()->routeIs('my-wedding-events.*') ||
         request()->routeIs('my-gallery-images.*') ||
         request()->routeIs('my-timeline-events.*') ||
         request()->routeIs('my-gifts.*') ||
         request()->routeIs('my-guests.*') ||
         request()->routeIs('my-people.*') ||
         request()->routeIs('my-guest-attendants.*') ||
         request()->routeIs('my-locations.*') ||
         request()->routeIs('my-invitations.*') ||
         request()->routeIs('my-guest-messages.*') ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseWedding"
            aria-expanded="{{ request()->routeIs('couples.*') ||
             request()->routeIs('people.*') ||
             request()->routeIs('wedding-events.*') ||
             request()->routeIs('locations.*') ||
             request()->routeIs('gallery-images.*') ||
             request()->routeIs('timeline-events.*') ||
             request()->routeIs('gifts.*') ||
             request()->routeIs('guests.*') ||
             request()->routeIs('invitations.*') ||
             request()->routeIs('guest-messages.*') ||
             request()->routeIs('my-couples.*') ||
             request()->routeIs('my-wedding-events.*') ||
             request()->routeIs('my-gallery-images.*') ||
             request()->routeIs('my-timeline-events.*') ||
             request()->routeIs('my-gifts.*') ||
             request()->routeIs('my-guests.*') ||
             request()->routeIs('my-people.*') ||
             request()->routeIs('my-guest-attendants.*') ||
             request()->routeIs('my-locations.*') ||
             request()->routeIs('my-invitations.*') ||
             request()->routeIs('my-guest-messages.*') ? 'true' : 'false' }}" aria-controls="collapseWedding">
            <i class="fas fa-fw fa-heart"></i>
            <span>Wedding Data</span>
        </a>
        <div id="collapseWedding" class="collapse {{ request()->routeIs('couples.*') ||
         request()->routeIs('people.*') ||
         request()->routeIs('wedding-events.*') ||
         request()->routeIs('locations.*') ||
         request()->routeIs('gallery-images.*') ||
         request()->routeIs('timeline-events.*') ||
         request()->routeIs('gifts.*') ||
         request()->routeIs('guests.*') ||
         request()->routeIs('invitations.*') ||
         request()->routeIs('guest-messages.*') ||
         request()->routeIs('my-couples.*') ||
         request()->routeIs('my-wedding-events.*') ||
         request()->routeIs('my-gallery-images.*') ||
         request()->routeIs('my-timeline-events.*') ||
         request()->routeIs('my-gifts.*') ||
         request()->routeIs('my-guests.*') ||
         request()->routeIs('my-people.*') ||
            request()->routeIs('my-guest-attendants.*') ||
            request()->routeIs('my-locations.*') ||
         request()->routeIs('my-invitations.*') ||
         request()->routeIs('my-guest-messages.*') ? 'show' : '' }}" aria-labelledby="headingWedding" data-parent="#accordionSidebar">
            <div class="py-2 bg-white rounded collapse-inner">
                @if(auth()->user()->role=="admin")
                <a class="collapse-item {{ request()->routeIs('couples.*') ? 'active' : '' }}" href="{{ route('couples.index') }}">Couples</a>
                <a class="collapse-item {{ request()->routeIs('people.*') ? 'active' : '' }}" href="{{ route('people.index') }}">People</a>
                <a class="collapse-item {{ request()->routeIs('wedding-events.*') ? 'active' : '' }}" href="{{ route('wedding-events.index') }}">Wedding Events</a>
                <a class="collapse-item {{ request()->routeIs('locations.*') ? 'active' : '' }}" href="{{ route('locations.index') }}">Locations</a>
                <a class="collapse-item {{ request()->routeIs('gallery-images.*') ? 'active' : '' }}" href="{{ route('gallery-images.index') }}">Gallery Images</a>
                <a class="collapse-item {{ request()->routeIs('timeline-events.*') ? 'active' : '' }}" href="{{ route('timeline-events.index') }}">Timeline Events</a>
                <a class="collapse-item {{ request()->routeIs('gifts.*') ? 'active' : '' }}" href="{{ route('gifts.index') }}">Gifts</a>
                <a class="collapse-item {{ request()->routeIs('guests.*') ? 'active' : '' }}" href="{{ route('guests.index') }}">Guests</a>
                <a class="collapse-item {{ request()->routeIs('guest-attendants.*') ? 'active' : '' }}" href="{{ route('guest-attendants.index') }}">Guest Attendants</a>
                <a class="collapse-item {{ request()->routeIs('invitations.*') ? 'active' : '' }}" href="{{ route('invitations.index') }}">Invitations</a>
                <a class="collapse-item {{ request()->routeIs('guest-messages.*') ? 'active' : '' }}" href="{{ route('guest-messages.index') }}">Guest Messages</a>
                @else
                <a class="collapse-item {{ request()->routeIs('my-couples.*') ? 'active' : '' }}" href="{{ route('my-couples.index') }}">My Couples</a>
                <a class="collapse-item {{ request()->routeIs('my-people.*') ? 'active' : '' }}" href="{{ route('my-people.index') }}">People</a>
                <a class="collapse-item {{ request()->routeIs('my-wedding-events.*') ? 'active' : '' }}" href="{{ route('my-wedding-events.index') }}">My Events</a>
                <a class="collapse-item {{ request()->routeIs('my-locations.*') ? 'active' : '' }}" href="{{ route('my-locations.index') }}">Locations</a>
                <a class="collapse-item {{ request()->routeIs('my-gallery-images.*') ? 'active' : '' }}" href="{{ route('my-gallery-images.index') }}">Gallery Images</a>
                <a class="collapse-item {{ request()->routeIs('my-timeline-events.*') ? 'active' : '' }}" href="{{ route('my-timeline-events.index') }}">Timeline Events</a>
                <a class="collapse-item {{ request()->routeIs('my-gifts.*') ? 'active' : '' }}" href="{{ route('my-gifts.index') }}">Gifts</a>
                <a class="collapse-item {{ request()->routeIs('my-guests.*') ? 'active' : '' }}" href="{{ route('my-guests.index') }}">Guests</a>
                <a class="collapse-item {{ request()->routeIs('my-guest-attendants.*') ? 'active' : '' }}" href="{{ route('my-guest-attendants.index') }}">Guest Attendants</a>
                <a class="collapse-item {{ request()->routeIs('my-invitations.*') ? 'active' : '' }}" href="{{ route('my-invitations.index') }}">Invitations</a>
                <a class="collapse-item {{ request()->routeIs('my-guest-messages.*') ? 'active' : '' }}" href="{{ route('my-guest-messages.index') }}">Guest Messages</a>
                @endif
            </div>
        </div>
    </li>

    <!-- Nav Item - Business Management Collapse Menu -->
    <li class="nav-item {{ request()->routeIs('packages.*') ||
     request()->routeIs('transactions.*') ||
     request()->routeIs('payment-methods.*') ||
     request()->routeIs('payment-transactions.*') ||
     request()->routeIs('create-order.*') ||
     request()->routeIs('my-transactions.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('packages.*') ||
         request()->routeIs('transactions.*') ||
         request()->routeIs('payment-methods.*') ||
         request()->routeIs('payment-transactions.*') ||
         request()->routeIs('create-order.*') ||
         request()->routeIs('my-transactions.*') ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseBusiness"
            aria-expanded="{{ request()->routeIs('packages.*') ||
             request()->routeIs('transactions.*') ||
             request()->routeIs('payment-methods.*') ||
             request()->routeIs('payment-transactions.*') ||
             request()->routeIs('create-order.*') ||
             request()->routeIs('my-transactions.*') ? 'true' : 'false' }}" aria-controls="collapseBusiness">
            <i class="fas fa-fw fa-briefcase"></i>
            <span>Business Management</span>
        </a>
        <div id="collapseBusiness" class="collapse {{ request()->routeIs('packages.*') ||
         request()->routeIs('transactions.*') ||
         request()->routeIs('payment-methods.*') ||
         request()->routeIs('payment-transactions.*') ||
         request()->routeIs('create-order.*') ||
         request()->routeIs('my-transactions.*') ? 'show' : '' }}" aria-labelledby="headingBusiness" data-parent="#accordionSidebar">
            <div class="py-2 bg-white rounded collapse-inner">
                 @if(auth()->user()->role=="admin")
                <a class="collapse-item {{ request()->routeIs('packages.*') ? 'active' : '' }}" href="{{ route('packages.index') }}">Packages</a>
                @endif
                <a class="collapse-item {{ request()->routeIs('create-order.*') ? 'active' : '' }}" href="{{ route('create-order.step1') }}">Create Order</a>
                @if(auth()->user()->role=="admin")
                <a class="collapse-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">Invoice</a>
                <a class="collapse-item {{ request()->routeIs('payment-methods.*') ? 'active' : '' }}" href="{{ route('payment-methods.index') }}">Payment Methods</a>
                <a class="collapse-item {{ request()->routeIs('payment-transactions.*') ? 'active' : '' }}" href="{{ route('payment-transactions.index') }}">Payment Invoice</a>
                @else
                <a class="collapse-item {{ request()->routeIs('my-transactions.*') ? 'active' : '' }}" href="{{ route('my-transactions.index') }}">My Invoice</a>
                @endif
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="border-0 rounded-circle" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->