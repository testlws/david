<template>
<v-container>
    <v-layout row wrap mt-6 class="justify-center">
      <v-flex xs12 sm9 md5 lg4>
        <v-card dark style="background:rgba(0,0,0,0.2)">
            <v-card-title primary-title>
              <h4>Reset your password</h4>
            </v-card-title>
<validation-observer
    ref="observer"
    v-slot="{ invalid }"
  >
            <v-form ref="form" autocomplete="off" @submit.prevent="resetPassword" v-if="!success" method="post">
              <v-card-text class="text--primary">
                <validation-provider
        v-slot="{ errors }"
        name="Email"
        rules="required|email"
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
                <v-btn type="submit" color="info" primary large block><v-icon
          left
          dark
        >
          mdi-account-plus-outline
        </v-icon>
        Update</v-btn>
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
    computed: {
      snackbar: function() {
          return this.$store.getters.snackbar;
      },
    },
    data() {
      return {
        token: null,
        email: null,
        password: null,
        password_confirmation: null,
        has_error: false
      }
    },
    methods: {
        resetPassword() {
            this.$http.post("/auth/reset/password", {
                token: this.$route.params.token,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation
            })
            .then(result => {
                // console.log(result.data);
                this.$router.push({name: 'login'})
            }, error => {
                console.error(error);
            });
        }
    }
  }
</script>