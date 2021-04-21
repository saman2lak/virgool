<template>
  <v-container>
    <v-row>
      <v-col>
        <v-form class="editor">
          <v-text-field
            v-model="title"
            @input="updateDraft"
            placeholder="عنوان متن..."
          ></v-text-field>
          <tiptap-editor
            v-model="content"
            @input="updateDraft"
            ref="tiptap"
          ></tiptap-editor>
          <v-btn
            color="info"
            class="mt-10"
            v-if="showSavingDraft"
            @click.stop="saveDraft"
          >
            ذخیره پیشنویس
          </v-btn>
        </v-form>
      </v-col>
    </v-row>

    <!-- snackbar Notify -->
    <v-snackbar v-model="snackbars.saved" :timeout="3000" color="info" left>
      پیشنویس شما به درستی ذخیره شد
      <v-btn color="white" text @click="snackbars.saved = false"> بستن </v-btn>
    </v-snackbar>

    <v-snackbar v-model="snackbars.error" :timeout="3000" color="error" left>
      مشکلی در ذخیره پیشنویس شما به وجود آمده است
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
  setup(props, context) {
    const title = ref("");
    const content = ref("");
    const requestType = ref("post");
    const requestUrl = ref("/api/posts/create");
    const snackbars = reactive({
      saved: false,
      error: false,
    });

    let link = context.root.$route.params.link;
    if (link) {
      requestType.value = "patch";
      requestUrl.value = "/api/drafts/" + link;
      axios
        .get(`/api/drafts/${link}`)
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
    }

    const setContent = (event) => {
      content.value = event;
    };

    const showSavingDraft = computed(() => {
      return title.value && content.value;
    });

    const updateDraft = debounce(function () {
      updateDraftStore()
        .then((res) => {
          if (requestType.value === "post") {
            history.pushState({}, "", res.data.link);
            requestUrl.value = `/api${res.data.link}`;
          }
          requestType.value = "patch";
          snackbars.saved = true;
        })
        .catch(() => {
          snackbars.error = true;
        });
    }, 3000);

    const saveDraft = () => {
      updateDraft.cancel();
      updateDraftStore().then((res) => {
        // context.root.$router.push(`/drafts/${context.root.$route.params.link}`);
        context.root.$router.push(`${res.data.link}/save`);
      });
    };

    const updateDraftStore = () => {
      return context.root.$store.dispatch("draft/saveDraft", {
        requestType: requestType.value,
        requestUrl: requestUrl.value,
        title: title.value,
        content: content.value,
      });
    };

    return {
      title,
      content,
      requestType,
      requestUrl,
      snackbars,
      setContent,
      updateDraft,
      showSavingDraft,
      saveDraft,
    };
  },
};
</script>

<style>
</style>
