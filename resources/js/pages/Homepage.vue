<template>
    <v-row>

        <sidebar v-bind:id=this.id></sidebar>

        <v-col md="9" class="mb-3">
            <v-row align="stretch">
                <v-col v-for="link in links" :key="link.id" lg="4" md="6" class="mb-4">
                    <v-card class="h-100">
                        <a href="#"><v-img :src="`/storage/images/${link.image}`" alt=""/></a>
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
                </v-col>
            </v-row>
        </v-col>
    </v-row>
    <!-- /.row -->

</template>

<script>
    import Sidebar from "../components/Sidebar";

    export default {
        props: ['id'],
        data() {
            return {
                links: {}
            }
        },
        mounted() {
            axios.get('/api/links/'+ this.id)
                .then(response => {
                    this.links = response.data.data;
                });
        },
        watch: {
            id: function (val) {
                axios.get('/api/links/'+ this.id)
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
