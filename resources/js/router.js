import VueRouter from 'vue-router'

import Homepage from './pages/Homepage.vue'
import Category from './pages/Category.vue'
import Site from './pages/Site.vue'
import About from './pages/About.vue'
import Contact from './pages/Contact.vue'
import Register from './components/Auth/Register.vue'
import Login from './components/Auth/Login.vue'
import ForgotPassword from './components/Auth/ForgotPassword.vue'
import ResetPasswordForm from './components/Auth/ResetPasswordForm.vue'
import NotFound from './components/NotFound.vue'

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
        path: '/category/:id/:slug',
        name: 'category',
        props: true,
        component: Category
    },
    {
        path: '/link/:linkId/:slug',
        name:'link',
        props: true,
        component: Site
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
    },
    { 
        path: '/reset-password', 
        name: 'reset-password', 
        component: ForgotPassword, 
        meta: { 
        auth:false 
        } 
    },
    { 
        path: '/reset-password/:token', 
        name: 'reset-password-form', 
        component: ResetPasswordForm, 
        meta: { 
        auth:false 
        } 
    },    
    {path: '*', component: NotFound}
]

const router = new VueRouter({
  history: true,
  mode: 'history',
  routes,
})

export default router