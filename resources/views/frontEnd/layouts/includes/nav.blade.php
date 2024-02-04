<div class="navbar-collapse collapse" id="navbarCollapse">
    <ul class="navbar-nav navbar-nav-scroll me-auto">

        <!-- Services item Listing -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="listingMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Services</a>
            <ul class="dropdown-menu" aria-labelledby="listingMenu">
                <!-- Dropdown submenu -->
                <li class="dropdown-submenu dropend">
                    @foreach ($services as $item)
                        <li> <a class="dropdown-item" href="{{ route('frontEnd.services.details',$item->slug) }}">{{ $item->title }}</a></li>								
                    @endforeach
                </li>
            </ul>
        </li>
        <!-- Blogs item Pages -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="pagesMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Blogs</a>
            <ul class="dropdown-menu" aria-labelledby="pagesMenu">
                <li>
                    <a class="dropdown-item" href="{{ route('frontEnd.blogs.index') }}">All</a>
                </li>
                <!-- Dropdown submenu -->
                <li class="dropdown-submenu dropend">
                    @foreach ($categories as $item)
                    <a class="dropdown-item dropdown-toggle" href="#">{{ $item->title}}</a>
                    <ul class="dropdown-menu" data-bs-popper="none">
                        @foreach ($item->posts as $post)
                            <li> <a class="dropdown-item" href="{{ route('frontEnd.blogs.details',$post->slug) }}">{{ $post->title }}</a></li>
                        @endforeach
                    </ul>
                        
                    @endforeach
                </li>             
            </ul>
        </li>

        <!-- Join Us -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="pagesMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Join Us</a>
            <ul class="dropdown-menu" aria-labelledby="pagesMenu">
                <li>
                    <a class="dropdown-item" href="{{ route('frontEnd.affiliate.index') }}">Affiliate</a>
                    <a class="dropdown-item" href="{{ route('frontEnd.chauffeur.index') }}">Chauffeur</a>
                </li>
            </ul>
        </li>

    <!-- Nav item link-->
    <li class="nav-item"> <a class="nav-link" href="{{ route('frontEnd.fleets.index') }}">Fleet</a></li>
    <li class="nav-item"> <a class="nav-link" href="{{ route('frontEnd.aboutUs.index') }}">About Us</a></li>

     <!-- Help Us -->
     <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Help Us</a>
        <ul class="dropdown-menu" aria-labelledby="pagesMenu">
            <li>
                <a class="dropdown-item" href="{{ route('frontEnd.policy.terms_condition') }}">Terms & Condations</a>
                <a class="dropdown-item" href="{{ route('frontEnd.policy.privacy_policy') }}">Privacy Policy</a>
                <a class="dropdown-item" href="{{ route('frontEnd.faq.index') }}">FAQs</a>
                <a class="dropdown-item" href="{{ route('frontEnd.contactUs.index') }}">Contact Us</a>
            </li>
        </ul>
    </li>
    </ul>
</div>