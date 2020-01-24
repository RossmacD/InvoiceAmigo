<template>
    <div>
        <h1></h1>
        <button></button>
    </div> 
</template>

<script>
    import axios from 'axios';
    export default {
        name:login,
         data() {
            return {
                customers: null
            }
        },
        methods:{
            login(){

            }
        },
        mounted () {
            axios
            .get('/api/customer/')
            .then(response => (this.customers = response.data.data))
        }
    }
</script>
