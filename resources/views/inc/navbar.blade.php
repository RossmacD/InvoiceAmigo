  <header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'InvoiceAmigo') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
        {{-- <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="{{route('invoices.index')}}" class="nav-link">Invoices</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('invoices.create')}}" class="nav-link">New Invoice</a>
                </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @if (Route::has('login'))
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            @endguest
            @endif
        </ul> --}}
        
        {{-- <form class="form-inline mt-2 mt-md-0">
            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form> --}}

        <ul class="navbar-nav ml-auto">
              @auth
                  <li class="nav-item">
                      <a href="{{ route('invoices.index') }}" class="nav-link">Invoices</a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('invoices.create') }}" class="nav-link">New Invoice</a>
                  </li>
                  <li class="nav-item dropdown">
                      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                          {{ Auth::user()->name }} <span class="caret"></span>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                          <a class="dropdown-item" href="{{ route('home') }}">
                              Dashboard
                          </a>
                          <a class="dropdown-item" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                              Logout
                          </a>
                          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                              @csrf
                          </form>
                      </div>
                  </li>
              @else
                  <li class="nav-item">
                      <a href="{{ route('login') }}" class="nav-link">Login</a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('register') }}" class="nav-link">Register</a>
                  </li>
              @endauth
          </ul>
    </div>
  </nav>
</header>