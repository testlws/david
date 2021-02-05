<template>
    <v-app-bar app style="background-color:#1797c2;" dark>
        <v-toolbar-title>LWS</v-toolbar-title>
        <v-spacer></v-spacer>
        <accountlinks></accountlinks>
    </v-app-bar>
</template>

<script>
    import AccountLinks from "../components/AccountLinks";

    export default {
    data: () => ({
      isLoading: false,
      search: null,
    }),

    computed: {
      token() {
          let token = document.head.querySelector('meta[name="csrf-token"]');
          return token.content
      }
    },
    methods: {
      logout() {
          document.getElementById('logout-form').submit()
      }
    },
    watch: {
    },

    components: {
      'accountlinks': AccountLinks
    }
  }
</script>