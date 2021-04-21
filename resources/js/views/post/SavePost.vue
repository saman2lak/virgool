<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="6">
        <p>عکس خود را آپلود کنید</p>
        <input type="file" @change="uploadImage" />
        <v-img
          :src="form.image"
          v-if="form.image"
          width="300px"
          height="300px"
          class="mt-5"
        ></v-img>
        <p class="mt-3">{{ form.title }}</p>
        <v-textarea
          v-model="form.desc"
          outlined
          label="توضیحات نوشته"
        ></v-textarea>
      </v-col>
      <v-col cols="12" md="6">
        <p>دسته بندی ها را اضافه کنید</p>
        <v-combobox
          v-model="form.categories"
          multiple
          chips
          outlined
          append-icon=""
          label="دسته بندی ها"
          hint="حداکثر 5 دسته بندی میتوانید وارد نمایید"
          persistent-hint
        ></v-combobox>

        <div class="mt-5 d-flex flex-row">
          <v-spacer></v-spacer>
          <v-btn color="info" @click="sendForm">ذخیره نوشته</v-btn>
        </div>
      </v-col>
    </v-row>

    <v-snackbar
      v-model="error.show"
      v-for="(error, index) in errors"
      :key="index"
      color="error"
    >
      {{ error.text }}
      <v-btn @click="error.show = false" text>بستن</v-btn>
    </v-snackbar>
  </v-container>
</template>

<script>
import { ref, reactive, watch } from "@vue/composition-api";
import uploadBase64 from "../../modules/file/uploadBase64";
import axios from "axios";
export default {
  setup(props, context) {
    const form = reactive({
      image: null,
      image_name: null,
      title: null,
      content: null,
      desc: null,
      categories: [],
    });

    const errors = ref([]);

    context.root.$store
      .dispatch("draft/getDraft", context.root.$route.params.link)
      .then((res) => {
        form.title = res.title;
        form.content = res.content;
      });

    const uploadImage = (event) => {
      form.image = uploadBase64(event);
      form.image_name = event.target.files[0].name;
    };

    watch(
      () => form.categories,
      (value) => {
        if (value.length > 5) {
          form.categories.pop();
        }
      }
    );

    const sendForm = () => {
      context.root.$store
        .dispatch("post/savePost", form)
        .then((res) => {
          context.root.$router.push({
            name: "postShow",
            params: { slug: res.data.data.slug },
          });
        })
        .catch((err) => {
          Object.values(err.response.data.errors).forEach((e) => {
            errors.value.push({
              text: e[0],
              show: true,
            });
          });
        });
    };

    return { form, errors, uploadImage, sendForm };
  },
};
</script>

<style>
</style>
