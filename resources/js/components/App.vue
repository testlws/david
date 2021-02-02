<template>
    <v-app id="main">
        <navbar ref="navbar"></navbar>
        <app-header @toggle-drawer="$refs.navbar.drawer = !$refs.navbar.drawer" style="background-color: rgba(0, 0, 0, 0.2) !important;"></app-header>

        <v-main style="background: url('/images/sidebar_bg1.jpg'); background-attachment: fixed; background-size:cover; background-position:bottom; background-repeat:no-repeat;">
            <v-container fluid>
                <router-view></router-view>
                <Snackbar></Snackbar>
            </v-container>
        </v-main>

        <app-footer></app-footer>
    </v-app>

</template>

<script>
    import Navbar from './Navbar'
    import Header from './Header'
    import Footer from './Footer'
    import Snackbar from './Snackbar'

    export default {
        data() {
            return {
                snackbar: {
                    appear: false,
                    icon: '',
                    text: '',
                    color: 'info',
                    x: 'center',
                    y: 'bottom',
                    timeout: -1,
                    actionBtn: false
                }                
            }
        },
        mounted() {
            this.$store.dispatch('LOAD_CATEGORIES');
        },
        name: 'App',
        components: {
            'navbar': Navbar,
            'app-header': Header,
            'app-footer': Footer,
            Snackbar
        },
    }
</script>