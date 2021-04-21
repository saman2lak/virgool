<template>
  <div>
    <v-dialog v-model="imageModal" max-width="360px">
      <v-card>
        <v-card-title>عکس خود را آپلود کنید</v-card-title>
        <vue-dropzone
          id="dropzone"
          :options="dropzoneOptions"
          @vdropzone-success="upload"
        ></vue-dropzone>
        <v-card-actions>
          <v-btn @click="imageModal = false">بستن</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { ref } from "@vue/composition-api";
import vueDropzone from "vue2-dropzone";

export default {
  components: { vueDropzone },
  // Setup Composition Api
  setup(_props, context) {
    const imageCommand = ref(null);
    const imageModal = ref(false);
    const dropzoneOptions = ref({
      url: "/api/uplaod-post-image",
      headers: { "X-CSRF-TOKEN": window.csrf_token },
      thumbnailWidth: 150,
      maxFilesize: 20,
    });

    const showModal = (command) => {
      imageModal.value = true;
      imageCommand.value = command;
    };

    const upload = (_file, res) => {
      const data = {
        imageCommand: imageCommand.value,
        attrs: {
          src: res.data,
        },
      };
      context.emit("image", data);
      imageModal.value = false;
      imageCommand.value = null;
    };

    return { imageCommand, imageModal, dropzoneOptions, showModal, upload };
  },
};
</script>

<style>
</style>
