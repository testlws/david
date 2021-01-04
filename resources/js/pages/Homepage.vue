<template>
    <v-row>

        <sidebar v-bind:id=this.id v-bind:categories=this.categories></sidebar>
        <v-col md="9" class="mb-3">
            <v-row align="stretch">
                <v-col v-for="link in links" :key="link.id" lg="4" md="6" class="mb-4">
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
                                :value="4.5"
                                color="amber"
                                dense
                                half-increments
                                readonly
                                size="14"
                                style="padding-bottom: 16px;"
                                ></v-rating>

                                <div class="grey--text ml-4" style="padding-bottom: 16px;">
                                4.5 (413)
                                </div>
                            </v-row>

                            {{ link.description }}</v-card-text>
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
                if (this.id == undefined || !this.currentCategory) {
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
                    return {
                        title: `${this.currentCategory[0].title} - Coin Guide`,
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
                tmp: '',
            }
        },
        computed: {
            currentCategory: function() {
                if (this.id == undefined) return false;

                this.tmp = this.categories.filter(c => c.id == this.id);
                console.log(this.tmp);
                return this.tmp;
            }
        },
        mounted() {
            axios.get('categories')
                .then(response => {
                    this.categories = response.data.data;
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
