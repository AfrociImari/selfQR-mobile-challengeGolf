<!-- eslint-disable prettier/prettier -->
<template>
  <v-app>
    <!-- Fixed Toolbar -->
    <v-app-bar color="#219640" dark flat app>
      <v-toolbar-title style="font-weight: bold;">チャレンジゴルフ</v-toolbar-title>
    </v-app-bar>

    <!-- Page Content -->
    <v-main>
      <v-container fluid class="pt-4">

        <!-- Modals -->

        <slot />

      </v-container>
    </v-main>
  </v-app>
</template>
<script>

export default {
    components: {
    },
    props: {
        admissionDataParam: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
        };
    },

    computed: {
    },
    mounted() {
    },

    beforeUnmount() {
    },
    methods: {
    },
};
</script>

<style>
</style>
