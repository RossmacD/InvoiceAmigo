<template>
    <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <router-link class="navbar-brand" to="/example">Laravel/Vue</router-link> -->
    <b-nav class="navbar navbar-expand-md navbar-dark  bg-dark">
        <router-link class="navbar-brand" to="/">
            InvoiceAmigo
        </router-link>
        <b-button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </b-button>
        <b-nav-item active to="/products">Products</b-nav-item>
        
        <!-- <div class="collapse navbar-collapse" id="navbarCollapse">

            <ul class="navbar-nav ml-auto">
                @auth
                <li class="nav-item">
                    <a href="{{ route('invoices.index') }}" class="nav-link">Invoices</a>
                </li>

                <li class="nav-item">
                    <a href="{{route('products.index')}}" class="nav-link">Products</a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{route('stripe.index')}}" class="nav-link">Stripe</a>
                </li> --}}
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('home') }}">
                            Dashboard
                        </a>
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('invoices.create') }}" class="btn btn-success btn-sm nav-link text-white">+
                        Invoice</a>
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
        </div> -->
    </b-nav>
</template>

<script>
import Vue from 'vue'
import { ButtonPlugin,NavPlugin } from 'bootstrap-vue'
import PortalVue from 'portal-vue'
Vue.use(PortalVue)
// Install BootstrapVue
Vue.use(ButtonPlugin)
Vue.use(NavPlugin)
export default {
    
}
</script>