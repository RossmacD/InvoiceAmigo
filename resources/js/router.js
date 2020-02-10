import VueRouter from "vue-router";
import Login from "./pages/auth/Login";
import Register from "./pages/auth/Register";
import Home from "./pages/HomePage";
import InvoiceIndex from "./pages/invoices/InvoiceIndex";
import ProductIndex from "./pages/products/ProductIndex";
import ProductCreate from "./pages/products/ProductsCreate.vue";
import ProductUpdate from "./pages/products/ProductUpdate.vue";
import ServiceIndex from "./pages/services/ServiceIndex.vue";
import ServiceCreate from "./pages/services/ServicesCreate.vue";
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
        component: Home,
        meta:{
            depthIndex:1
        }
    },
    {
        path: "/login",
        name: "login",
        component: Login,
        beforeEnter: onlyLoggedOut,
        meta: {
            depthIndex: 5
        }
    },
    {
        path: "/register",
        name: "register",
        component: Register,
        beforeEnter: onlyLoggedOut,
        meta: {
            depthIndex: 6
        }
    },
    {
        path: "/products",
        name: "products",
        component: ProductIndex,
        beforeEnter: onlyLoggedIn,
        meta: {
            depthIndex: 3
        }
    },
    {
        path: "/products/create",
        name: "productcreate",
        component: ProductCreate,
        beforeEnter: onlyLoggedIn
    },
    {
        path: "/products/:id",
        name: "productupdate",
        component: ProductUpdate,
        beforeEnter: onlyLoggedIn
    },
    {
        path: "/services",
        name: "services",
        component: ServiceIndex,
        beforeEnter: onlyLoggedIn,
        meta: {
            depthIndex: 4
        }
    },
    {
        path: "/services/create",
        name: "servicecreate",
        component: ServiceCreate,
        beforeEnter: onlyLoggedIn
    },
    {
        path: "/invoices",
        name: "invoice",
        component: InvoiceIndex,
        beforeEnter: onlyLoggedIn,
        meta: {
            depthIndex: 2
        }
    }
];

const router = new VueRouter({
    history: true,
    mode: "history",
    routes
});

export default router;
