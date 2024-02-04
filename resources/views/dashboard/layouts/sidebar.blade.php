@php
    $routeName = Route::currentRouteName();
@endphp
<div class="sidebar-menu">
    <ul class="menu">
        <li class='sidebar-title'>Main Menu</li>
        @canany('analytics')
            <li class="sidebar-item {{ $routeName == 'dashboard.home' ? 'active' : '' }} ">
                <a href="{{ route('dashboard.home') }}" class='sidebar-link'>
                    <i data-feather="home" width="20"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        @endcanany

        @hasanyrole('Seo-admin|Super-admin')
            <li
                class="sidebar-item {{ ($routeName == 'dashboard.admins.index') | ($routeName == 'dashboard.admins.create') | ($routeName == 'dashboard.admins.edit') ? 'active' : '' }} ">
                <a href="{{ route('dashboard.admins.index') }}" class='sidebar-link'>
                    <i data-feather="shield" width="20"></i>
                    <span>Users</span>
                </a>
            </li>
            <li
                class="sidebar-item {{ ($routeName == 'dashboard.customers.index') | ($routeName == 'dashboard.customer.create') | ($routeName == 'dashboard.customer.edit') ? 'active' : '' }} ">
                <a href="{{ route('dashboard.customers.index') }}" class='sidebar-link'>
                    <i data-feather="shield" width="20"></i>
                    <span>Customers</span>
                </a>
            </li>
        @endhasanyrole
     
        
        @canany(['pending-blogs','pending-web-pages'])
            <li
                class="sidebar-item has-sub {{ ($routeName == 'dashboard.blogs.pending') | ($routeName == 'dashboard.pages.pending') | ($routeName == 'dashboard.categories.pending') ? 'active' : '' }}">
                <a href="#" class='sidebar-link'>
                    <i data-feather="triangle" width="20"></i>
                    <span>Pendings</span>
                    {{-- <span class="badge badge-danger">{{ $pending_number }}</span> --}}
                </a>

                <ul class="submenu active">
                    @canany('pending-blogs')
                        <li class="{{ $routeName == 'dashboard.blogs.pending' ? 'active' : '' }}">
                            <a href="{{ route('dashboard.blogs.pending') }}">Blogs</a>
                        </li>
                    @endcanany
                    @canany('pending-web-pages')
                        <li class="{{ $routeName == 'dashboard.pages.pending' ? 'active' : '' }}">
                            <a href="{{ route('dashboard.pages.pending') }}">Web Pages</a>
                        </li>
                    @endcanany
                </ul>

            </li>
        @endcanany

        @canany(['web-pages','create-web-pages','submit-web-pages'])
            <li
                class="sidebar-item {{ ($routeName == 'dashboard.pages.index') | ($routeName == 'dashboard.pages.create') | ($routeName == 'dashboard.pages.edit') ? 'active' : '' }} ">
                <a href="{{ route('dashboard.pages.index', 'Home') }}" class='sidebar-link'>
                    <i class="far fa-file-alt"></i>
                    <span>Web Pages</span>
                </a>
            </li>
        @endcanany

        @canany(['submit-blogs','blogs'])
            <li
                class="sidebar-item {{ $routeName == 'dashboard.blogs.index' || $routeName == 'dashboard.blogs.create' || $routeName == 'dashboard.blogs.edit' ? 'active' : '' }} ">
                <a href="{{ route('dashboard.blogs.index') }}" class='sidebar-link'>
                    <i data-feather="edit" width="20"></i>
                    <span>Blogs</span>
                </a>
            </li>
        @endcanany

        @canany('blog-categories')
            <li class="sidebar-item {{ $routeName == 'dashboard.categories.index' ? 'active' : '' }} ">
                <a href="{{ route('dashboard.categories.index') }}" class='sidebar-link'>
                    <i data-feather="grid" width="20"></i>
                    <span>Blogs Categorie</span>
                </a>
            </li>
        @endcanany
       
            {{-- --------------- --------------------- --}}
            {{-- --------------- reservations--------- --}}
            {{-- --------------- --------------------- --}}
        @hasanyrole('Super-admin|Driver')
            <li
                class="sidebar-item {{ $routeName == 'dashboard.reservations.index' || $routeName == 'dashboard.reservations.create' ? 'active' : '' }} ">
                <a href="{{ route('dashboard.reservations.index') }}" class='sidebar-link'>
                    <i data-feather="globe" width="20"></i>
                    <span>Reservations</span>
                </a>
            </li>
        @endhasanyrole
      
        @hasanyrole('Super-admin')

        <li
            class="sidebar-item has-sub {{ $routeName == 'dashboard.logs.index' || $routeName == 'dashboard.logs.create' || $routeName == 'dashboard.logs.edit' ? 'active' : '' }}">
            <a href="#" class='sidebar-link'>
                <i data-feather="rotate-ccw" width="20"></i>
                <span>Logs</span>
            </a>

            <ul class="submenu active">
                    <li class="{{ $routeName == 'dashboard.logs.userLogs' ? 'active' : '' }}">
                        <a href="{{ route('dashboard.logs.userLogs') }}">Users Logs</a>
                    </li>
              
                    {{-- <li class="{{ $routeName == 'dashboard.logs.documentsLog' ? 'active' : '' }}">
                        <a href="{{ route('dashboard.logs.documentsLog') }}">Driver Documents</a>
                    </li> --}}
                    <li class="{{ $routeName == 'dashboard.logs.blogLogs' ? 'active' : '' }}">
                        <a href="{{ route('dashboard.logs.blogLogs') }}">Blog Logs</a>
                    </li>
                    <li class="{{ $routeName == 'dashboard.logs.serviceLogs' ? 'active' : '' }}">
                        <a href="{{ route('dashboard.logs.serviceLogs') }}">Service Logs</a>
                    </li>
            </ul>

        </li>
      
       
            <li class="sidebar-item {{ $routeName == 'dashboard.drivers.index' ? 'active' : '' }} ">
                <a href="{{ route('dashboard.drivers.index') }}" class='sidebar-link'>
                    <i data-feather="truck" width="20"></i>
                    <span>Drivers</span>
                </a>
            </li>
            <li class="sidebar-item {{ $routeName == 'dashboard.companies.index' ? 'active' : '' }} ">
                <a href="{{ route('dashboard.companies.index') }}" class='sidebar-link'>
                    <i data-feather="briefcase" width="20"></i>
                    <span>Companies</span>
                </a>
            </li>
           

    @endhasanyrole


        @canany('keywords-banks')
            <li class="sidebar-item {{ $routeName == 'dashboard.keywords.index' ? 'active' : '' }} ">
                <a href="{{ route('dashboard.keywords.index') }}" class='sidebar-link'>
                    <i data-feather="key" width="20"></i>
                    <span>Keywords Bank</span>
                </a>
            </li>

            <li class="sidebar-item {{ $routeName == 'dashboard.tags.index' ? 'active' : '' }} ">
                <a href="{{ route('dashboard.tags.index') }}" class='sidebar-link'>
                    <i data-feather="tag" width="20"></i>
                    <span>Tags</span>
                </a>
            </li>
        @endcanany
        @canany(['show-comments','create-comments','edit-comments','delete-comments'])
            <li
                class="sidebar-item {{ ($routeName == 'dashboard.comments.index') | ($routeName == 'dashboard.comments.show') ? 'active' : '' }} ">
                <a href="{{ route('dashboard.comments.index') }}" class='sidebar-link'>
                    <i data-feather="message-circle" width="20"></i>
                    <span>Comments</span>
                    {{-- <span class="badge badge-danger textb-right">{{ $countComments }}</span> --}}
                </a>
            </li>
        @endcanany
        {{-- role,permissions --}}
        @hasanyrole('Super-admin')

        <li class="sidebar-item {{ ($routeName == 'dashboard.ads.index') | ($routeName == 'dashboard.ads.show') ? 'active' : '' }} ">
                <a href="{{ route('dashboard.ads.index') }}" class='sidebar-link'>
                    <i data-feather="mic" width="20"></i>
                    <span>Ads</span>
                </a>
            </li>

            <li
            class="sidebar-item has-sub {{ in_array($routeName, ['dashboard.trashes.index', 'dashboard.drivers.showSoftDelete', 'dashboard.reservations.showSoftDelete', 'dashboard.coupons.showSoftDelete']) ? 'active' : '' }}">
            <a href="#" class='sidebar-link'>
                <i data-feather="dollar-sign" width="20"></i>
                <span>Pricing</span>
            </a>

            <ul class="submenu">
                <li class="{{ $routeName == 'dashboard.countries.index' ? 'active' : '' }}">
                    <a href="{{ route('dashboard.countries.index') }}">Locations</a>
                </li>
                <li class="{{ $routeName == 'dashboard.fleet_category.index' ? 'active' : '' }}">
                    <a href="{{ route('dashboard.fleetCategories.index') }}">Fleet Category</a>
                </li>
                
                <li class="{{ $routeName == 'dashboard.events.index' || $routeName == 'dashboard.events.create' ? 'active' : '' }}">
                    <a href="{{ route('dashboard.events.index') }}">Events</a>
                </li>
                
                <li class="{{ $routeName == 'dashboard.childSeats.index' || $routeName == 'dashboard.childSeats.create' ? 'active' : '' }}">
                    <a href="{{ route('dashboard.childSeats.index') }}">Child Seats</a>
                </li>


                <li class="{{ $routeName == 'dashboard.coupons.index' ? 'active' : '' }}">
                    <a href="{{ route('dashboard.coupons.index') }}">Coupons</a>
                </li>


                <li class="{{ $routeName == 'dashboard.serviceLocationRestrictions.index' ? 'active' : '' }}">
                    <a href="{{ route('dashboard.serviceLocationRestrictions.index') }}">Location Rules</a>
                </li>
               
                {{-- <li class="{{ $routeName == 'dashboard.cities.index' ? 'active' : '' }}">
                    <a href="{{ route('dashboard.cities.index') }}">Cities</a>
                </li> --}}
              
               
               
            </ul>
        </li>
        
            {{-- Trash --}}
            <li
                class="sidebar-item has-sub {{ in_array($routeName, ['dashboard.trashes.index', 'dashboard.drivers.showSoftDelete', 'dashboard.reservations.showSoftDelete', 'dashboard.coupons.showSoftDelete']) ? 'active' : '' }}">
                <a href="#" class='sidebar-link'>
                    <i data-feather="triangle" width="20"></i>
                    <span>Trash</span>
                </a>

                <ul class="submenu">
                    <li class="{{ $routeName == 'dashboard.trashes.index' ? 'active' : '' }}">
                        <a href="{{ route('dashboard.trashes.index') }}">Users</a>
                    </li>

                    <li class="{{ $routeName == 'dashboard.drivers.showSoftDelete' ? 'active' : '' }}">
                        <a href="{{ route('dashboard.drivers.showSoftDelete') }}">Drivers</a>
                    </li>
                    

                    <li class="{{ $routeName == 'dashboard.reservations.showSoftDelete' ? 'active' : '' }}">
                        <a href="{{ route('dashboard.reservations.showSoftDelete') }}">Reservations</a>
                    </li>

                    <li class="{{ $routeName == 'dashboard.coupons.showSoftDelete' ? 'active' : '' }}">
                        <a href="{{ route('dashboard.coupons.showSoftDelete') }}">Coupons</a>
                    </li>
                    <li class="{{ $routeName == 'dashboard.companies.showSoftDelete' ? 'active' : '' }}">
                        <a href="{{ route('dashboard.companies.showSoftDelete') }}">Companies</a>
                    </li>
                </ul>
            </li>


            <li class="sidebar-item {{ $routeName == 'dashboard.mapLinking.index' ? 'active' : '' }} ">
                <a href="{{ route('dashboard.mapLinking.index') }}" class='sidebar-link'>
                    <i data-feather="link" width="20"></i>
                    <span>URL Redirection</span>
                </a>
            </li>

        @endhasanyrole






    </ul>
</div>
