import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        todos: [],
        todosLoading: false,
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
        todosLoading: state => {
            return state.todosLoading;
        },
        todos: state => {
            return state.todos;
        },
        snackbar: state => {
            return state.snackbar;
        }
    },
    mutations: {
        SET_TODOS_LOADING (state, todosLoading) {
            state.todosLoading = todosLoading;
        },
        SET_TODOS (state, todos) {
            state.todos = todos;
        },
        SET_SNACKBAR (state, snackbar) {
            state.snackbar = snackbar;
        }
    },
    actions: {
        SET_SNACKBAR: function (context, snackbar) {
            context.commit('SET_SNACKBAR', snackbar)
        },
        SET_TODOS_LOADING: function (context, todosLoading) {
            context.commit('SET_TODOS_LOADING', todosLoading)
        },
        LOAD_TODOS: async function (context) {
            // if (context.state.todos.length > 0) return true;

            context.dispatch('SET_TODOS_LOADING', true);

            axios.get('/todos').then((response) => {
                console.log(response);
                context.commit('SET_TODOS', response.data.data)
                context.dispatch('SET_TODOS_LOADING', false);
                return true;
            }, (err) => {
                console.log(err)
                context.dispatch('SET_TODOS_LOADING', false);
                return false;
            })
        },
    }
})

export default store