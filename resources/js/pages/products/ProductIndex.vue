<template>
   <div>
        <h2 class="text-center">All Products</h2>
        <!-- <a href="{{route('products.create')}}" class="btn btn-primary ">New Product</a> -->
        <ul class="list-group py-3 mb-3" v-if="products">
            <li class="list-group-item my-2"  v-for="product in products" v-bind:key="product.id">
                <h5>{{ product.product_name }}</h5>
                <h4 class="float-right">€{{ product.product_cost }} EUR</h4>
                <p>{{ product.product_description }}</p>
                <!-- <small class="float-right">product.created_at->diffForHumans() }}</small>
                <a href="{{route('products.show',$product->id)}}">View Details</a> -->
            </li>
        </ul>
    </div>
</template>


<script>
    import axios from 'axios';
    export default {
         data () {
            return {
                products: null
            }
        },
        mounted () {
            axios
            .get('/api/products/', { 
            headers:{
                    Authorization : "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNTg0MTdjZTFkZTVkOWM2N2ZiZTY3ZTEzMzA3NmYzOGQzYzZmMGFlZTcxYzAzNmY5ZmM5MjlhZGFjYTIxYjZmYWQzOTg4NTNjOTY3MDk2NGQiLCJpYXQiOjE1Nzk4OTY1NzAsIm5iZiI6MTU3OTg5NjU3MCwiZXhwIjoxNjExNTE4OTcwLCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.H4DlvtRmoKGCKora4NI5DGbhxkgTnyJoEVYs7jDF-TN9QmmKBWDtFtQf_nplKHgO9gLCDP5v_b4lISQSyYv2XGV7IgxPEXeTB7Rbu43WsWp7H_z-7q5M5CF04Htk0AOpcKZ58efqEMvcVMNOShCH0kKCwZIyIrJIrvvDyGyXjAj03JbG_mmU08s3pZdNH1Ld034e7OTaZVBuAf_6xmzZn1VYofdACDCGrUxzkyh0un0p-sRk_tKbYeW9B7tYTh3z-t_Ipfdc8qNc3bEU7xs0esrojkrr7je6IgEhKEdsGaesNDrIZEGJL5TZ1BqCY1vz1gBSlTWg06m24KW1FBlxYckfTHxV5M0rEe6z05IZeHXdiZ5j43Y5kUUfyDFYaIG4inBEVDX3wTtvzQXfrYc4FU0_DZuVsr9TfEp6XbdZalzmDTPs0xlSbfNY9uFXUrbc3CkcC12yKjDtbu-yMbJaC57HoajPH7WmC7nsmV1GlFgXj-EABKKOPBAQaxCS73QK4ICVs9TEYSO_f__5qcwQ5D41QwMAeoxtgHtZax-efePw2O4apDQ1y0yqiQCfWtv7ikXHY0Xi_XgGiN5NUYjQAd92xnf_OJCAJ37tdm--f8YhIo5E41KS91Ndt6t7LLcjpMift2S4NJLYuRuj4EB1Asw-sY1_D1oDDCNrCKpNfJA"
                }
            })
            .then(response => (this.products = response.data.products.data))
            .catch(
                
            )
        }
    }
</script>
