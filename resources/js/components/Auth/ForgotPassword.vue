<template>
<v-container>
    <v-layout row wrap mt-6 class="justify-center">
      <!-- xs12 and sm12 to make it responsive = 12 columns on mobile and 6 columns from medium to XL layouts -->
      <v-flex xs12 sm9 md5 lg4 space-around>
          <v-card>
            <v-card-title primary-title>
              <h4>Forgot my password</h4>
            </v-card-title>
<validation-observer
    ref="observer"
    v-slot="{ invalid }"
  >
            <v-form ref="form" @submit.prevent="requestResetPassword" lazy-validation>
            <v-card-text class="text--primary">
            <validation-provider
        v-slot="{ errors }"
        name="Email"
        rules="required|email"
      >
            <v-text-field prepend-icon="mdi-email" name="Email" label="Email" v-model="email" required :error-messages="errors"></v-text-field>
            </validation-provider>
            </v-card-text>
            <v-card-actions>
              <v-btn :loading="isLoading" type="submit" color="info" primary large block>Send password reset link<v-icon
          right
          dark
        >
          mdi-email-send-outline
        </v-icon>
</v-btn>
            </v-card-actions>
            </v-form>
            </validation-observer>
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
        has_error: false,
        isLoading: false,
      }
    },
    methods: {
        async requestResetPassword() {
          const isValid = await this.$refs.observer.validate();
          if (!isValid) return;

          this.isLoading=true;

          this.$http.post("/auth/reset-password", {email: this.email}).then(result => {
              this.response = result.data;
              console.log(result.data);
              this.$router.push({ name: 'login', query: { resetLinkSent: true }})
          }, error => {
              this.isLoading=false;
              var data = error.response.data;

              if (data.errors && data.errors.email.length) {               
                this.snackbar.icon = 'mdi-clock-time-five-outline';
                this.snackbar.text = data.errors.email[0]
                this.snackbar.color = "warning";
                this.snackbar.actionBtn = true;
                this.snackbar.appear = true;
                this.$store.dispatch('SET_SNACKBAR', this.snackbar);
              }
          });
        }
    }
  }
</script>