import VueRouter from 'vue-router'

import Todos from './pages/Todos.vue'
import Register from './components/Auth/Register.vue'
import Login from './components/Auth/Login.vue'
import ForgotPassword from './components/Auth/ForgotPassword.vue'
import ResetPasswordForm from './components/Auth/ResetPasswordForm.vue'
import NotFound from './components/NotFound.vue'

// Routes
const routes = [
    {
        path: '/',
        name: 'todos',
        component: Todos,
        meta: {
            auth: true
        }
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