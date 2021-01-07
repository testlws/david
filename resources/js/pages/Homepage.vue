<template>
    <v-row>
<v-snackbar
  v-model="snackbar.appear"
  :color="snackbar.color"
  :timeout="snackbar.timeout"
  :left="snackbar.x === 'left'"
  :right="snackbar.x === 'right'"
  :top="snackbar.y === 'top'"
> 
    {{ snackbar.text }}
    <template v-slot:action="{ attrs }">
        <v-btn
          text
          v-bind="attrs"
          :to="{ path: '/login', query: { from: currentRoutePath }}"
        >
          Login
        </v-btn>
    </template>
</v-snackbar>
        <sidebar v-bind:id=this.id v-bind:categories=this.categories v-bind:categoriesLoading=this.categoriesLoading></sidebar>
        <v-col cols="12" xs="12" md="9" class="mb-3">
            <v-row align="stretch">
                <v-col v-for="(link, index) in links" :key="link.id" lg="4" md="6" class="mb-4">
                    <v-lazy
        :options="{
          threshold: .5
        }"
        min-height="200"
        transition="fade-transition"
      >
                    <v-card class="h-100">
                        <a href="#" class="d-block" v-ripple="{ center: true }"><v-img :src="`/storage/images/${link.image}`" alt=""/></a>
                        <v-card-title>
                            <a href="#">{{ link.title }}</a>
                        </v-card-title>
                        <v-card-text>
                            <v-row
                                align="center"
                                class="mx-0"
                            >
                                <v-rating
                                :value="parseFloat(link.score)"
                                color="amber"
                                dense
                                half-increments
                                readonly
                                size="16"
                                style="padding-bottom: 16px;"
                                ></v-rating>

                                <div class="grey--text ml-4" style="padding-bottom: 16px;">
                                {{ link.score }} ({{ link.votes }} votes)
                                </div>
                            </v-row>

                            {{ link.description }}</v-card-text>
                            <v-card-actions>
      <v-btn :loading="likeLoading[index]" @click="like(link.id, index)" outlined
        text
        icon
        class="ml-2"
        color="green" dark
      >
        <v-icon dark left v-if="!link.liked">mdi-thumb-up-outline</v-icon>
        <v-icon dark left v-else-if="link.liked">mdi-thumb-up</v-icon>
      </v-btn>
        {{ link.likes }}
      <v-btn @click="dislike(link.id, index)" outlined
        text
        icon
        class="ml-2 ma-0"
        color="red" dark
      >
        <v-icon dark left v-if="!link.disliked">mdi-thumb-down-outline</v-icon>
        <v-icon dark left v-else-if="link.disliked">mdi-thumb-down</v-icon>
      </v-btn> {{ link.dislikes }}
                            </v-card-actions>
                    </v-card>
                    </v-lazy>
                </v-col>
            </v-row>
        </v-col>
    </v-row>
    <!-- /.row -->

</template>

<script>
    import Sidebar from "../components/Sidebar";

    export default {
        props: [
            'id'
        ],
        metaInfo() {
                if (this.id == undefined || !this.currentCategory.length) {
                    return {
                        title: `Home - Coin Guide`,
                        meta: [
                            { name: 'description', content: `Welcome to the best crypto links on Coin Guide`}
                            /*
                            { property: 'og:title', content: this.userData.name + ' - Epiloge'},
                            { property: 'og:site_name', content: 'Epiloge'},
                            { property: 'og:description', content: 'Connect and follow ' + this.userData.name + ' on Epiloge - ' + this.userData.tagline},
                            {property: 'og:type', content: 'profile'},
                            {property: 'og:url', content: 'https://epiloge.com/@' + this.userData.username},
                            {property: 'og:image', content: this.aws_url + '/users/' + this.userData.profileurl + '-main.jpg' }    
                            */
                        ]
                    }
                } else {
                    const title = this.currentCategory[0].title;
                    return {
                        title: `${title} - Coin Guide`,
                        meta: [
                            { name: 'description', content: `${this.currentCategory[0].description}`}
                            /*
                            { property: 'og:title', content: this.userData.name + ' - Epiloge'},
                            { property: 'og:site_name', content: 'Epiloge'},
                            { property: 'og:description', content: 'Connect and follow ' + this.userData.name + ' on Epiloge - ' + this.userData.tagline},
                            {property: 'og:type', content: 'profile'},
                            {property: 'og:url', content: 'https://epiloge.com/@' + this.userData.username},
                            {property: 'og:image', content: this.aws_url + '/users/' + this.userData.profileurl + '-main.jpg' }    
                            */
                        ]
                    }
                }
            },
            data() {
            return {
                categories: [],
                links: [],
                likeLoading: {},
                dislikeLoading: {},
                categoriesLoading: true,
                snackbar: {
                    appear: false,
                    icon: 'mdi-account-circle',
                    text: 'You must be logged in.',
                    color: 'info',
                    timeout: 2500,
                    x: 'center',
                    y: 'bottom',
                },                
            }
        },
        computed: {
            currentRoutePath: function() {
                return this.$route.path;
            },
            currentCategory: function() {
                if (this.id == undefined) return undefined;
                var tmp = this.categories.filter(c => c.id == this.id);
                return tmp;
            }
        },
        methods: {
            like: function(link_id, index) {
                if (!this.$auth.check()) {
                    this.snackbar.appear = true;
                    return
                }
                this.likeLoading[index] = true;
                var index = this.$_.findIndex(this.links, {id: link_id})
                var link = this.links[index]

                if (link.liked)  {
                    axios.post('links/'+ link_id +'/unlike')
                    .then(response => {
                        if (response.data.status && response.data.status == 'link_unliked') this.$set(this.links[index], 'liked', false)
                        this.likeLoading[index] = false;
                        this.$set(this.links[index], 'likes', response.data.likes)
                        this.$set(this.links[index], 'dislikes', response.data.dislikes)
                        this.$set(this.links[index], 'score', response.data.score)
                        this.$set(this.links[index], 'votes', response.data.votes)
                    });
                } else {
                    axios.post('links/'+ link_id +'/like')
                    .then(response => {
                        if (response.data.status && response.data.status == 'link_liked') {
                            this.$set(this.links[index], 'liked', true)
                            this.$set(this.links[index], 'disliked', false)
                            this.$set(this.links[index], 'likes', response.data.likes)
                            this.$set(this.links[index], 'dislikes', response.data.dislikes)
                            this.$set(this.links[index], 'score', response.data.score)
                            this.$set(this.links[index], 'votes', response.data.votes)
                        }
                        this.likeLoading[index] = false;
                    });
                }
            },
            dislike: function(link_id) {
                if (!this.$auth.check()) {
                    this.snackbar.appear = true;
                    return
                }

                var index = this.$_.findIndex(this.links, {id: link_id})
                var link = this.links[index]

                if (link.disliked)  {
                    axios.post('links/'+ link_id +'/undislike')
                    .then(response => {
                        if (response.data.status && response.data.status == 'link_undisliked') this.$set(this.links[index], 'disliked', false)
                        this.$set(this.links[index], 'likes', response.data.likes)
                        this.$set(this.links[index], 'dislikes', response.data.dislikes)
                        this.$set(this.links[index], 'score', response.data.score)
                        this.$set(this.links[index], 'votes', response.data.votes)
                    });
                } else {
                    axios.post('links/'+ link_id +'/dislike')
                    .then(response => {
                        if (response.data.status && response.data.status == 'link_disliked') {
                            this.$set(this.links[index], 'disliked', true)
                            this.$set(this.links[index], 'liked', false)
                            this.$set(this.links[index], 'likes', response.data.likes)
                            this.$set(this.links[index], 'dislikes', response.data.dislikes)
                            this.$set(this.links[index], 'score', response.data.score)
                            this.$set(this.links[index], 'votes', response.data.votes)
                        }
                    });
                }

            },
        },
        mounted() {
            axios.get('categories')
                .then(response => {
                    this.categories = response.data.data;
                    this.categoriesLoading = false;
                    this.$meta().refresh();
            });

            if (this.id !== undefined) {
                axios.get('links/'+ this.id)
                    .then(response => {
                        this.links = response.data.data;
                    });
            }
        },
        watch: {
            id: function (val) {
                axios.get('links/'+ this.id)
                    .then(response => {
                        this.links = response.data.data;
                    });
            },
        },        
        components: {
            'sidebar': Sidebar
        }
    }
</script>
