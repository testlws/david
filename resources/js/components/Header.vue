<template>
    <v-app-bar app color="indigo" dark>
        <v-app-bar-nav-icon @click.stop="$emit('toggle-drawer')"></v-app-bar-nav-icon>
        <v-toolbar-title>Coin Guide</v-toolbar-title>
        <v-spacer></v-spacer>
        <accountlinks></accountlinks>
        <v-col cols="12" sm="6" md="3">
        <v-autocomplete
                v-model="model"
                :items="items"
                :loading="isLoading"
                :search-input.sync="search"
                color="white"
                hide-no-data
                hide-selected
                item-text="title"
                item-value="API"
                label="Search "
                placeholder="Start typing to Search"
                prepend-icon="mdi-database-search"
                return-object
                filled
                dense
                hide-details
        >
<template slot="item" slot-scope="{ item, tile }">
        <v-list-tile-content style="width:100%">
            <div class="float-left">{{ item.title }}</div>

<v-chip
      class="ma-1 float-right"
      color="red"
      text-color="white"
    >
      Red Chip
    </v-chip>
        </v-list-tile-content>
    </template>
            </v-autocomplete>        
        </v-col>
    </v-app-bar>
</template>

<script>
    import AccountLinks from "../components/AccountLinks";

    export default {
    data: () => ({
      titleLimit: 20,
      entries: [],
      isLoading: false,
      model: null,
      search: null,
    }),

    computed: {
      token() {
          let token = document.head.querySelector('meta[name="csrf-token"]');
          return token.content
      },
      fields () {
        if (!this.model) return []

        return Object.keys(this.model).map(key => {
          return {
            key,
            value: this.model[key] || 'n/a',
          }
        })
      },
      items () {
        return this.entries.map(entry => {
          const title = entry.title.length > this.titleLimit
            ? entry.title.slice(0, this.titleLimit) + '...'
            : entry.title

          return Object.assign({}, entry, { title })
        })
      },
    },
    methods: {
      logout() {
          document.getElementById('logout-form').submit()
      }
    },
    watch: {
      search (val) {
        // Items have already been loaded
        if (this.items.length > 0) return

        // Items have already been requested
        if (this.isLoading) return

        this.isLoading = true

        // Lazily load input items
        fetch('/api/links')
          .then(res => res.json())
          .then(res => {
            const count = res.data.length;
            const entries= res.data;
            this.count = count
            this.entries = entries
          })
          .catch(err => {
            console.log(err)
          })
          .finally(() => (this.isLoading = false))
      },
    },

    components: {
      'accountlinks': AccountLinks
    }
  }
</script>