<template>
    <div>
        <Navbar></Navbar>
        <main role="main" class="container">
            <div class="mt-4">
                <!-- Content -->
                <router-view></router-view>
            </div>
        </main>
    </div>
</template>

<script>
import Vue from 'vue'
import Navbar from '../components/Navbar.vue'

    export default {
        name: "App",
        components:{
            Navbar
        }
    }
</script>

