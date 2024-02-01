import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('../views/Login.vue') // Lazy load component
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
