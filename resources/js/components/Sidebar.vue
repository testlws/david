<template>
    <v-col cols="12" xs="12" md="3">
        <v-card dark :loading="categoriesLoading" style="background:rgba(0,0,0,0.5);">
            <v-list flat dense style="background:transparent;">
                <v-subheader>CATEGORIES</v-subheader>
                <v-list-item-group style="background:transparent;"
                mandatory
                color="deep-purple accent-1"
            >
                <v-list-item v-for="(category, index) in categories" :key="`item-${index}`" :value="category.id" link :to="{ name: 'category', params: { id: category.id, slug: category.slug }}">
                    <v-list-item-icon>
                        <v-icon v-text="category.icon"></v-icon>
                    </v-list-item-icon>
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
        props: ['id', 'categories', 'categoriesLoading'],
        data() {
            return {
            }
        },
        mounted() {
        },
    }
</script>