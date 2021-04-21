<template>
  <v-container>
    <v-row>
      <v-col>
        <v-form class="editor">
          <v-text-field
            v-model="title"
            @input="updatePost"
            placeholder="عنوان متن..."
          ></v-text-field>
          <tiptap-editor
            v-model="content"
            @input="updatePost"
            ref="tiptap"
          ></tiptap-editor>
          <v-btn
            color="info"
            class="mt-10"
            v-if="showSavingPost"
            @click.stop="savePost"
          >
            ویرایش نوشته ها
          </v-btn>
        </v-form>
      </v-col>
    </v-row>

    <!-- snackbar Notify -->
    <v-snackbar v-model="snackbars.saved" :timeout="3000" color="info" left>
      نوشته شما به درستی ویرایش شد
      <v-btn color="white" text @click="snackbars.saved = false"> بستن </v-btn>
    </v-snackbar>

    <v-snackbar v-model="snackbars.error" :timeout="3000" color="error" left>
      مشکلی در ویرایش نوشته شما به وجود آمده است
      <v-btn color="white" text @click="snackbars.saved = false"> بستن </v-btn>
    </v-snackbar>
    <!-- snackbar Notify -->
  </v-container>
</template>

<script>
import TiptapEditor from "../../components/TipTapEditor";
import { debounce } from "lodash";
import { reactive, ref, computed } from "@vue/composition-api";

import axios from "axios";
export default {
  components: {
    TiptapEditor,
  },
  metaInfo: {
    title: "ویرایش پست",
  },
  setup(props, context) {
    const title = ref("");
    const content = ref("");
    const snackbars = reactive({
      saved: false,
      error: false,
    });

    axios
      .get(`/api/posts/${context.root.$route.params.slug}/edit`)
      .then((res) => {
        title.value = res.data.title;
        content.value = res.data.content;
        context.refs.tiptap.editor.setContent(res.data.content);
      })
      .catch((err) => {
        if (err.response.status === 404) {
          context.root.$router.push({ name: "notfound" });
        }
        if (err.response.status === 403) {
          context.root.$router.push({ name: "accessdenied" });
        }
      });

    const setContent = (event) => {
      content.value = event;
    };

    const showSavingPost = computed(() => {
      return title.value && content.value;
    });

    const updatePost = debounce(function () {
      updatePostStore()
        .then((res) => {
          snackbars.saved = true;
        })
        .catch(() => {
          snackbars.error = true;
        });
    }, 3000);

    const savePost = () => {
      updatePost.cancel();
      updatePostStore().then((res) => {
        context.root.$router.push(
          `/posts/${context.root.$route.params.slug}/update`
        );
        // context.root.$router.push(`${res.data.link}/save`);
      });
    };

    const updatePostStore = () => {
      return context.root.$store.dispatch("post/updatePost", {
        title: title.value,
        content: content.value,
        slug: context.root.$route.params.slug,
      });
    };

    return {
      title,
      content,
      snackbars,
      setContent,
      updatePost,
      showSavingPost,
      savePost,
    };
  },
};
</script>

<style>
</style>
