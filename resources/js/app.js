require('./bootstrap');

import Vue from 'vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter)
import routes from './routes'

Vue.component('login-button', require('./components/LoginButtonComponent.vue'));
Vue.component('register-button', require('./components/RegisterButtonComponent.vue'));
Vue.component('remember-password', require('./components/RememberPasswordComponent.vue'));
Vue.component('reset-password', require('./components/ResetPasswordComponent.vue'));
Vue.component('snackbar', require('./components/SnackBarComponent.vue'));
Vue.component('gravatar', require('./components/GravatarComponent.vue'));

import Vuetify from 'vuetify'
Vue.use(Vuetify)

import VueMeta from 'vue-meta'
Vue.use(VueMeta, {
    // optional pluginOptions
    refreshOnceOnNavigation: true
})

import App from './components/App.vue'

const app = new Vue({
    el: '#app',
    components: { App },
    router: new VueRouter(routes),
    vuetify: new Vuetify()
})
