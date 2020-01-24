import VueRouter from 'vue-router'
import ExampleComponent from "./components/ExampleComponent";
import ExampleComponent2 from "./components/ExampleComponent2";


const routes = [
    {path: '/', 
    name: 'example',
    component: ExampleComponent},

    {
        path: '/example',
        name: 'example2',
        component: ExampleComponent2
    },
    {
        path: '/customers',
        name: 'custine',
        component: ExampleComponent2
    }
]

const router = new VueRouter({
    history: true,
    mode: 'history',
    routes,
})

export default router