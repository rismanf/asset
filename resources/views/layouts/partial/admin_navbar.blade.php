<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light text-sm">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-user mr-2"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                {{ Auth::user()->name }}
                            </h3>
                            {{-- <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p> --}}
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
