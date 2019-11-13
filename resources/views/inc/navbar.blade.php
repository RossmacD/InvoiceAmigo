  <header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'InvoiceAmigo') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
        
        <ul class="navbar-nav ml-auto">
              @auth
                  <li class="nav-item">
                      <a href="{{ route('invoices.index') }}" class="nav-link">Invoices</a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('invoices.create') }}" class="nav-link">New Invoice</a>
                  </li>
                  <li class="nav-item">
                      <a href="{{route('products.index')}}" class="nav-link">Products</a>
                  </li>
                  <li class="nav-item">
                        <a href="{{route('stripe.index')}}" class="nav-link">Stripe</a>
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
