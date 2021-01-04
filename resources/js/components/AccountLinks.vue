<template>
    <v-btn
        color="info"
        class="ma-2 white--text"
        v-if="!$auth.check()"
        link :to="`/login`"
      >
        Login
        <v-icon
          right
          dark
        >
          mdi-login
        </v-icon>
    </v-btn>
        <v-menu bottom left v-else-if="$auth.check()">
              <template v-slot:activator="{ on, attrs }">
              <v-btn
                dark
                icon
                v-bind="attrs"
                v-on="on"
              >
                <v-icon>mdi-account-circle</v-icon>
              </v-btn>
            </template>

          <v-list>
              <v-list-item @click.prevent="$auth.logout()">
                  <v-list-item-title>Logout</v-list-item-title>
              </v-list-item>
          </v-list>
        </v-menu>
</template>

<script>
  export default {
    data() {
      return {
        routes: {
          // UNLOGGED
          unlogged: [
            {
              name: 'Inscription',
              path: 'register'
            },
            {
              name: 'Connexion',
              path: 'login'
            }
          ],

          // LOGGED USER
          user: [
            {
              name: 'Dashboard',
              path: 'dashboard'
            }
          ],
          // LOGGED ADMIN
          admin: [
            {
              name: 'Dashboard',
              path: 'admin.dashboard'
            }
          ]
        }
      }
    },
    mounted() {
      console.log("account links mounted");
    }
  }
</script>