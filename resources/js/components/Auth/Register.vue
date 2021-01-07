<template>
<v-container>
    <v-layout row wrap mt-6 class="justify-center">
      <v-flex xs12 sm9 md5 lg4>
        <v-card flat>
            <v-card-title primary-title>
              <h4>Create your account. It's FREE.</h4>
            </v-card-title>
<validation-observer
    ref="observer"
    v-slot="{ invalid }"
  >
            <v-form ref="form" autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
              <v-card-text class="text--primary">
                <validation-provider
        v-slot="{ errors }"
        name="Name"
        rules="required"
      >
                <v-text-field prepend-icon="mdi-email" name="name" label="Name" v-model="name" required :error-messages="errors"></v-text-field>
                </validation-provider>
                <validation-provider
        v-slot="{ errors }"
        name="Email"
        rules="required|email|emailAlreadyExists"
      >
                <v-text-field prepend-icon="mdi-email" name="email" label="Email" v-model="email" required :error-messages="errors"></v-text-field>
                </validation-provider>
            <validation-provider
        v-slot="{ errors }"
        name="Password"
        rules="required"
        vid="password"
      >
                <v-text-field prepend-icon="mdi-lock" name="password" label="Password" type="password" v-model="password" required :error-messages="errors"></v-text-field>
  </validation-provider>
            <validation-provider
        v-slot="{ errors }"
        name="Password confirmation"
        rules="required|confirmed:password"
      >
                <v-text-field prepend-icon="mdi-lock" name="password_confirmation" label="Password confirmation" type="password" v-model="password_confirmation" required :error-messages="errors"></v-text-field>
      </validation-provider>
              </v-card-text>
              <v-card-actions>
                <v-btn type="submit" color="info" primary large block>Register</v-btn>
              </v-card-actions>
            </v-form>
            </validation-observer>
          </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>
<script>
import { required, confirmed, digits, email, max, regex } from 'vee-validate/dist/rules'
import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'
setInteractionMode('eager')

extend('confirmed', {
    ...confirmed,
    message: 'Password confirmation does not match the password',
  })

extend('digits', {
    ...digits,
    message: '{_field_} needs to be {length} digits. ({_value_})',
  })

  extend('required', {
    ...required,
    message: '{_field_} can not be empty',
  })

  extend('max', {
    ...max,
    message: '{_field_} may not be greater than {length} characters',
  })

  extend('regex', {
    ...regex,
    message: '{_field_} {_value_} does not match {regex}',
  })

  extend('email', {
    ...email,
    message: 'Email must be valid',
  })

  export default {
    components: {
      ValidationProvider,
      ValidationObserver,
    },    
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
created() {
        extend('emailAlreadyExists',{
    getMessage: field => field + ' already exists.',
    validate: value => {
        return new Promise(resolve => {
            axios.get('/skills', {email: value})
            .then(function (response) {
                console.log(response.data[0]);
                resolve({
                    valid: response.data[0],
                    data: value !== 'trigger' ? undefined : {message: 'Not this value'}
                });
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            .then(function () {
                // always executed
            });
        });
      }
    });
    },
    methods: {
      async register() {
        const isValid = await this.$refs.observer.validate();
        if (!isValid) return

        var redirectObj = this.$auth.redirect()
        var app = this

        this.$auth.register({
          data: {
            name: app.name,
            email: app.email,
            password: app.password,
            password_confirmation: app.password_confirmation
          }}).then((response) => {
            app.success = true
            this.$auth.login({
              rememberMe: true,
              fetchUser: true,
              data: {
                email: app.email,
                password: app.password
              },
              redirect: {
                path: this.from ? this.from : (redirectObj ? redirectObj.from.path : '/')
              },
            }).then((response) => {
            }).catch((error) => {
                console.log(error.response)
                app.has_error = true
            });        
            
        }).catch((error) => {
            console.log(error)
            app.has_error = true
            app.error = error
            app.errors = error.response.data.errors || {}
        });
      }
    }
  }
</script>