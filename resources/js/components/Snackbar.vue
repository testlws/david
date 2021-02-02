<template>
    <v-snackbar v-model="snackbar.appear" :timeout="snackbar.timeout" :color="snackbar.color" transition="slide-y-reverse-transition"
        :left="snackbar.x === 'left'" :right="snackbar.x === 'right'" :top="snackbar.y === 'top'">
        <v-icon small>{{ snackbar.icon }}</v-icon> {{ snackbar.text }}
        <template v-slot:action="{ attrs }">
            <v-btn v-if="snackbar.actionBtn" color="white" text v-bind="attrs" @click="snackbar.appear = false">
                Close
            </v-btn>
        </template>
    </v-snackbar>
</template>

<script>
    export default {
        computed: {
            snackbar: function() {
                return this.$store.getters.snackbar;
            },
        },
        methods: {
        },
        data() {
            return {
            }
        },
        mounted() {
        },
        name: 'Snackbar',
        components: {
        },
    }
</script>