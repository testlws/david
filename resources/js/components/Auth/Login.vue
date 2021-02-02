<template>
<v-container>
    <v-layout row wrap mt-6 class="justify-center">
      <!-- xs12 and sm12 to make it responsive = 12 columns on mobile and 6 columns from medium to XL layouts -->
      <v-flex xs12 sm9 md5 lg4 space-around>
          <v-card dark style="background:rgba(0,0,0,0.2)">
                  <div class="text-center float-right pa-4"><v-icon small class="mr-1">mdi-lock-question</v-icon><router-link
  :to="{ path: '/reset-password' }" class="deep-purple--text text--accent-1">Forgot my password</router-link></div>
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
              <v-btn :loading="isLoading" type="submit" color="info" primary large block>Login        <v-icon
          right
          dark
        >
          mdi-login
        </v-icon>
</v-btn>
            </v-card-actions>
            </v-form>
            </validation-observer>
            </v-card>
  </v-flex>

    </v-layout>    
  <br/>
    <v-layout row wrap mt-6 class="justify-center">

            <v-flex xs12 sm9 md5 lg4 space-around>

      <div class="mt-8 pa-2 justify-center text-center">
        <v-card dark style="background:rgba(0,0,0,0.2)">
          <v-card-title>
        You don't have an account on our site yet ?
        </v-card-title>
        
        <v-card-actions>
        <v-btn type="submit" color="success" class="mt-2" primary large block link :to="{ path: '/register'}">
<v-icon
          left
          dark
        >
          mdi-account-plus-outline
        </v-icon>
        Register</v-btn>
        </v-card-actions>
        </v-card>
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
    computed: {
      snackbar: function() {
          return this.$store.getters.snackbar;
      },
    },
    data() {
      return {
        email: null,
        password: null,
        has_error: false,
        isLoading: false,
        from: this.$route.query.from,
        resetLinkSent: this.$route.query.resetLinkSent,
        userNotFound: this.$route.query.userNotFound,
        emailVerified: this.$route.query.emailVerified,
      }
    },

    mounted() {
            if (this.resetLinkSent) {
                this.snackbar.icon = 'mdi-email-send-outline';
                this.snackbar.text = "A password reset link has been sent to your e-mail.";
                this.snackbar.color = "info";
                this.snackbar.actionBtn = true;
                this.snackbar.appear = true;
            }

            if (this.emailVerified) {
                this.snackbar.icon = 'mdi-email-check-outline';
                this.snackbar.text = "Your e-mail address has been verified.";
                this.snackbar.color = "success";
                this.snackbar.actionBtn = true;
                this.snackbar.appear = true;
            }

            if (this.userNotFound) {
                this.snackbar.icon = 'mdi-alert-circle-outline';
                this.snackbar.text = "This user could not be found.";
                this.snackbar.color = "error";
                this.snackbar.actionBtn = true;
                this.snackbar.appear = true;
            }

            this.$store.dispatch('SET_SNACKBAR', this.snackbar);
    },

    methods: {
      async login() {
        const isValid = await this.$refs.observer.validate();
        if (!isValid) return

        this.isLoading=true;

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
            this.isLoading=false;
            console.log(error.response)
            app.has_error = true
        });        
      }
    }
  }
</script>