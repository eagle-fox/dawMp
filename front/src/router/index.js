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
    component: () => import('../views/Login.vue'), // Lazy load component
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/Register.vue'), // Lazy load component
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('../views/Dashboard.vue'), // Lazy load component
  },
  {
    path: '/profile',
    name: 'Profile',
    component: () => import('../views/Profile.vue'), // Lazy load component
  }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
