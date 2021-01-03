import VueRouter from 'vue-router'

import Homepage from './pages/Homepage.vue'
import About from './pages/About.vue'
import Contact from './pages/Contact.vue'
import Sidebar from './components/Sidebar.vue'
import AuthLayout from './components/Layouts/AuthLayout.vue'
import Register from './components/Auth/Register.vue'
import Login from './components/Auth/Login.vue'
import ResetEmail from './components/Auth/ResetEmail.vue'
import ResetPassword from './components/Auth/ResetPassword.vue'


// Routes
const routes = [
    {
        path: '/',
        name: 'home',
        component: Homepage,
    },
    {
        path: '/about',
        name: 'about',
        component: About,
    },
    {
        path: '/contact',
        name: 'contact',
        component: Contact,
    },
    {
        path: '/category/:id/offers',
        props: true,
        component: Homepage
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }                    
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            auth: false
        }                    
    }
]

const router = new VueRouter({
  history: true,
  mode: 'history',
  routes,
})

export default router