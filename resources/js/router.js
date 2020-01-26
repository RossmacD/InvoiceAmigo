import VueRouter from 'vue-router'
import Login from "./pages/auth/Login";
import ProductIndex from "./pages/products/ProductIndex";


const routes = [
    {path: '/', 
    name: 'login',
    component: Login},
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