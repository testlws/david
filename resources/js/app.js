require('./bootstrap');

import Vue from 'vue'
import auth from './auth'
import router from './router'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import Vuetify from 'vuetify'
Vue.use(Vuetify)

import VueMeta from 'vue-meta'
Vue.use(VueMeta, {
    refreshOnceOnNavigation: true
})

import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios)

import VueAuth               from '@websanova/vue-auth/src/v2.js';
import driverAuthBearer      from '@websanova/vue-auth/src/drivers/auth/bearer.js';
import driverHttpAxios       from '@websanova/vue-auth/src/drivers/http/axios.1.x.js';
import driverRouterVueRouter from '@websanova/vue-auth/src/drivers/router/vue-router.2.x.js';

Vue.use(VueAuth, {
    plugins: {
        http: Vue.axios, // Axios
        router: router,
    },
    drivers: {
        auth: driverAuthBearer,
        http: driverHttpAxios,
        router: driverRouterVueRouter,
    },
    options: auth
});


//Vue.use(VueAuth, auth)

/*
Vue.use(auth, {
    auth: driverAuthBearer,
    http: driverHttpAxios,
    router: driverRouterVueRouter,
    rolesVar: 'role'
});
*/

import App from './components/App.vue'

export const vm = new Vue({
    el: '#app',
    components: { 
        App,
    },
    router: router,
    vuetify: new Vuetify()
})
