<template>
  <div>
    <v-img
      :src="data.image"
      max-height="300px"
      min-height="300px"
      class="card-img-overlay d-flex align-end white--text rounded-lg"
      gradient="to top right, rgba(100,115,201,.33), rgba(25,32,72,.6)"
    >
      <v-card-title>{{ data.title }}</v-card-title>
      <v-card-actions class="mr-4 mb-4">
        <v-avatar>
          <v-img :src="data.user.profile"></v-img>
        </v-avatar>
        <div class="d-flex flex-column mr-2">
          <span class="body-2">{{ data.user.name }}</span>
          <span class="caption">4 هفته پیش</span>
        </div>
      </v-card-actions>
    </v-img>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      required: true,
    },
  },
};
</script>

<style>
</style>
