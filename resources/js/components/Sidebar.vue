<template>
    <v-col md="3">
        <v-card>
            <v-list dense>
                <v-subheader>CATEGORIES</v-subheader>
                <v-list-item-group
                mandatory
                color="indigo"
            >
                <v-list-item v-for="(category, index) in categories" :key="`item-${index}`" :value="category.id" link :to="`/category/${category.id}/offers`">
                    <v-list-item-content>
                        <v-list-item-title>{{ category.title }} ({{ category.links_count }})</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                </v-list-item-group>
            </v-list>
        </v-card>
    </v-col>
</template>

<script>
    export default {
        props: ['id', 'categories'],
        data() {
            return {
            }
        },
        mounted() {
        },
    }
</script>