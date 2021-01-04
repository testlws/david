<template>
  <v-container>
    <v-layout row class="text-xs-center justify-center">
      <v-flex xs3 style="background-image: url('/images/login.jpg'); background-position:center center;">
        <v-card color="transparent" height="500px"></v-card>
      </v-flex>
      <v-flex xs4 class="grey lighten-4">
        <v-container style="position: relative;top: 13%;" class="text-xs-center">
          <v-card flat>
            <v-card-title primary-title>
              <h4>Login</h4>
            </v-card-title>
            <v-form @submit.prevent="login">
            <v-card-text class="text--primary">
            <v-alert
                  prominent
                  type="error"
                  v-if="has_error"
                >
              <v-row align="center">
                <v-col class="grow">
                  We were unable to log you in using the credentials you supplied. 
                </v-col>
              </v-row>
            </v-alert>
            <v-text-field prepend-icon="mdi-email" name="Email" label="Email" v-model="email"></v-text-field>
            <v-text-field prepend-icon="mdi-lock" name="Password" label="Password" type="password" v-model="password"></v-text-field>
            </v-card-text>
            <v-card-actions>
              <v-btn type="submit" color="info" primary large block>Login</v-btn>
            </v-card-actions>
            </v-form>
          </v-card>
        </v-container>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  export default {
    data() {
      return {
        email: null,
        password: null,
        has_error: false
      }
    },

    mounted() {
      //
    },

    methods: {
      login() {
        // get the redirect object
        var redirect = this.$auth.redirect()
        var app = this
        this.$auth.login({
          rememberMe: true,
          fetchUser: true,
          data: {
            email: app.email,
            password: app.password
          }
        }).then((response) => {
            const redirectTo = redirect ? redirect.from.name : 'home'

            this.$router.push({name: redirectTo})
        }).catch((error) => {
            console.log(error.response)
            app.has_error = true
        });        
      }
    }
  }
</script>