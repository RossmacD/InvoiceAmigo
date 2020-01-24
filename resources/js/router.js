import VueRouter from 'vue-router'
import Home from "./pages/Homepage";
import ProductIndex from "./pages/products/ProductIndex.vue";


const routes = [
    {path: '/', 
    name: 'home',
    component: Home},
    {
        path: '/products',
        name: 'product',
        component: ProductIndex
    }
]

const router = new VueRouter({
    history: true,
    mode: 'history',
    routes,
})

export default router