import VueRouter from "vue-router";
import Login from "./pages/auth/Login";
import Home from "./pages/HomePage";
import InvoiceIndex from "./pages/invoices/InvoiceIndex";
import ProductIndex from "./pages/products/ProductIndex";
import store from "./store";

//Middleware
//Reroutes to home if authenticated
const onlyLoggedOut = (to, from, next) => {
    if (!store.getters.isAuthenticated) {
        next()
        return
    }
    next('/')
}
//Reroutes to login if not authenticated
const onlyLoggedIn = (to, from, next) => {
    if (store.getters.isAuthenticated) {
        next()
        return
    }
    next('/login')
}

const routes = [
    {
        path: "/",
        name: "home",
        component: Home
    },
    {
        path: "/login",
        name: "login",
        component: Login,
        beforeEnter: onlyLoggedOut
    },
    {
        path: "/products",
        name: "product",
        component: ProductIndex,
        beforeEnter: onlyLoggedIn
    },
    {
        path: "/invoices",
        name: "invoice",
        component: InvoiceIndex,
        beforeEnter: onlyLoggedIn
    }
];

const router = new VueRouter({
    history: true,
    mode: "history",
    routes
});

export default router;
