<template>
    <v-app id="main" style="background-color:#2a2a2a;">
        <app-header @toggle-drawer="$refs.navbar.drawer = !$refs.navbar.drawer"></app-header>

        <v-main>
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