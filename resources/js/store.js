import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        currentCategory: {},
        categories: [],
        categoriesLoading: false,
        snackbar: {
        appear: false,
        icon: '',
        text: '',
        color: 'info',
        x: 'center',
        y: 'bottom',
        timeout: 5000,
        actionBtn: false
      },
    },
    getters: {
        currentCategory: state => {
            return state.currentCategory;
        },
        categoriesLoading: state => {
            return state.categoriesLoading;
        },
        categories: state => {
            return state.categories;
        },
        snackbar: state => {
            return state.snackbar;
        }
    },
    mutations: {
        SET_CURRENT_CATEGORY (state, currentCategory) {
            state.currentCategory = currentCategory;
        },
        SET_CATEGORIES_LOADING (state, categoriesLoading) {
            state.categoriesLoading = categoriesLoading;
        },
        SET_CATEGORIES (state, categories) {
            state.categories = categories;
        },
        SET_SNACKBAR (state, snackbar) {
            state.snackbar = snackbar;
        }
    },
    actions: {
        SET_CURRENT_CATEGORY: function (context, currentCategory) {
            context.commit('SET_CURRENT_CATEGORY', currentCategory)
        },
        SET_SNACKBAR: function (context, snackbar) {
            context.commit('SET_SNACKBAR', snackbar)
        },
        SET_CATEGORIES_LOADING: function (context, categoriesLoading) {
            context.commit('SET_CATEGORIES_LOADING', categoriesLoading)
        },
        LOAD_CATEGORIES: async function (context) {
            if (context.state.categories.length > 0) return true;

            context.dispatch('SET_CATEGORIES_LOADING', true);
            axios.get('/categories').then((response) => {
                context.commit('SET_CATEGORIES', response.data.data)
                context.dispatch('SET_CATEGORIES_LOADING', false);
                return true;
            }, (err) => {
                console.log(err)
                context.dispatch('SET_CATEGORIES_LOADING', false);
                return false;
            })
        },
    }
})

export default store