<template>
<v-container>
    <v-layout row wrap mt-6 class="justify-center">
      <!-- xs12 and sm12 to make it responsive = 12 columns on mobile and 6 columns from medium to XL layouts -->
      <v-flex xs12 sm9 md5 lg4>
          <v-card flat>
            <v-card-title primary-title>
              <h4>Login</h4>
            </v-card-title>
<validation-observer
    ref="observer"
    v-slot="{ invalid }"
  >
            <v-form ref="form" @submit.prevent="login" lazy-validation>
            <v-card-text class="text--primary">
            <validation-provider
        v-slot="{ errors }"
        name="Email"
        rules="required|email"
      >
            <v-text-field prepend-icon="mdi-email" name="Email" label="Email" v-model="email" required :error-messages="errors"></v-text-field>
            </validation-provider>
            <validation-provider
        v-slot="{ errors }"
        name="Password"
        rules="required"
      >
            <v-text-field prepend-icon="mdi-lock" name="Password" label="Password" type="password" v-model="password" required :error-messages="errors"></v-text-field>
            </validation-provider>
            </v-card-text>
            <v-card-actions>
              <v-btn type="submit" color="info" primary large block>Login        <v-icon
          right
          dark
        >
          mdi-login
        </v-icon>
</v-btn>
            </v-card-actions>
            </v-form>
            </validation-observer>
      <div class="mt-8 pa-2 justify-center text-center">
        You don't have an account on our site yet ?<br/>
        Create one for FREE !<br/>
        <v-btn type="submit" color="info" class="mt-2" primary large block link :to="{ path: '/register'}">
<v-icon
          left
          dark
        >
          mdi-account-plus-outline
        </v-icon>
        Register</v-btn>
      </div>
          </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import { required, digits, email, max, regex } from 'vee-validate/dist/rules'
import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'
setInteractionMode('eager')

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
        email: null,
        password: null,
        has_error: false,
        from: this.$route.query.from
      }
    },

    mounted() {
    },

    methods: {
      async login() {
        const isValid = await this.$refs.observer.validate();
        if (!isValid) return

        // get the redirect object
        var redirectObj = this.$auth.redirect()
        var app = this
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
//            const redirectTo = redirect ? redirect.from.name : 'home'

//            this.$router.push({name: redirectTo})
        }).catch((error) => {
            console.log(error.response)
            app.has_error = true
        });        
      }
    }
  }
</script>