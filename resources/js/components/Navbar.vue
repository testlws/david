<template>
    <v-navigation-drawer 
    v-model="drawer" app src="/images/sidebar_bg1.jpg" dark>
        <v-list dense>
            <v-list-item link exact :to="{ name: 'home' }">
                <v-list-item-action>
                    <v-icon>mdi-home</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Home</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item link exact :to="{ name: 'about' }">
                <v-list-item-action>
                    <v-icon>mdi-information</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>About</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item link exact :to="{ name: 'contact' }">
                <v-list-item-action>
                    <v-icon>mdi-email</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Contact</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
        <template v-slot:prepend v-if="$auth.check()">
        <v-list>
            <v-list-item link>
                <v-list-item-avatar>
                    <v-img :src="$auth.user().avatar"></v-img>
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title class="title" :title="$auth.user().name">
                    {{ $auth.user().name }}
                    </v-list-item-title>
                    <v-list-item-subtitle :title="$auth.user().email">{{ $auth.user().email }}</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-action>
                    <v-icon>mdi-menu-down</v-icon>
                </v-list-item-action>
            </v-list-item>
        </v-list>
            <div class="pa-2">
            <v-btn block @click.prevent="$auth.logout()">
                Logout
            </v-btn>
            </div>
            <v-divider></v-divider>
        </template>
    </v-navigation-drawer>
</template>

<script>
    export default {
        data() {
            return {
                drawer: false
            }
        },
        mounted() {

        },
    }
</script>
