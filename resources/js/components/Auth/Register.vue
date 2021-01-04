<template>
  <v-container class="text-xs-center justify-center">
    <v-card style="max-width:1000px">
    <v-row class="text-xs-center justify-center">
    <v-col cols="4" class="pa-0">
        <v-card class="rounded-0" style="flex-grow:1; background-image: url('/images/register.jpg'); background-position:center center;" color="transparent" min-height="500px"></v-card>
    </v-col>
    <v-col cols="8">
        <v-card flat>
            <v-card-title primary-title>
              <h4>Create your account. It's FREE.</h4>
            </v-card-title>
            <v-form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
              <v-card-text class="text--primary">
              <v-alert
                  prominent
                  type="error"
                  v-if="has_error && !success"
                >
                <v-row align="center">
                  <v-col class="grow">
                    Please, correct the errors below to proceed with the creation of yoour account.
                  </v-col>
                </v-row>
              </v-alert>                
                <v-text-field prepend-icon="mdi-email" name="email" label="Email" v-model="email"></v-text-field>
                <v-text-field prepend-icon="mdi-lock" name="password" label="Password" type="password" v-model="password"></v-text-field>
                <v-text-field prepend-icon="mdi-lock" name="password_confirmation" label="Password confirmation" type="password" v-model="password_confirmation"></v-text-field>
              </v-card-text>
              <v-card-actions>
                <v-btn type="submit" color="info" primary large block>Register</v-btn>
              </v-card-actions>
            </v-form>
          </v-card>
    </v-col>
    </v-row>
    </v-card>
  </v-container>

    
</template>
<script>
  export default {
    data() {
      return {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        has_error: false,
        error: '',
        errors: {},
        success: false
      }
    },

    methods: {
      register() {
        var app = this
        this.$auth.register({
          data: {
            email: app.email,
            password: app.password,
            password_confirmation: app.password_confirmation
          }}).then((response) => {
            app.success = true
            this.$router.push({name: 'login', params: {successRegistrationRedirect: true}})
        }).catch((error) => {
            app.has_error = true
            app.error = error
            app.errors = error.response.data.errors || {}
        });
      }
    }
  }
</script>