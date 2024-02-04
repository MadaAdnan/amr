<div class="navbar-collapse collapse" id="navbarCollapse">
    <ul class="navbar-nav navbar-nav-scroll mx-auto">
        @foreach ($categories as $item)
            <li class="nav-item"> <a class="nav-link" href="{{ route('frontEnd.categories.blogs',$item->slug) }}">{{ $item->title }}</a></li>
        @endforeach
        <!-- Nav item Components -->
    </ul>
</div>