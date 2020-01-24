

<template>
    <div>
        <Navbar></Navbar>
        <router-view></router-view>
    </div>
</template>

<script>
import Vue from 'vue'
import Navbar from './Navbar.vue'

    export default {
        name: "App",
        components:{
            Navbar
        }
    }
</script>

