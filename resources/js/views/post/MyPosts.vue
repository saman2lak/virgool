<template>
  <div>
    <v-main>
      <v-container>
        <v-row>
          <v-col cols="12">
            <div class="mt-10 d-flex flex-row">
              <span class="headline font-weight-bold">نوشته های شما</span>
              <v-spacer></v-spacer>
              <v-btn color="info" outlined :to="{ name: 'create-post' }"
                >نوشتن پست جدید</v-btn
              >
            </div>

            <v-tabs
              class="tw_tracking-normal mt-5"
              color="gray darken-3"
              v-model="menu"
            >
              <v-tab href="#draft" @click="fetchAllDraft" class="body-1">
                پیش نویس ها
                <template v-if="drafts_count"> ({{ drafts_count }}) </template>
              </v-tab>
              <v-tab href="#posts" @click="fetchAllPost" class="body-1">
                مطالب منتشر شده
                <template v-if="posts_count"> ({{ posts_count }}) </template>
              </v-tab>
            </v-tabs>
            <v-divider></v-divider>
            <v-tabs-items v-model="menu">
              <v-tab-item value="draft">
                <div
                  class="my-5"
                  v-for="(draft, index) in drafts"
                  :key="draft.id"
                >
                  <router-link
                    :to="{ name: 'update-draft', params: { link: draft.link } }"
                    class="title"
                  >
                    {{ draft.title || "بدون عنوان" }}
                  </router-link>
                  <div class="d-flex flex-row">
                    <span class="body-2 gray--text"
                      >آخرین ویرایش:
                      {{ draft.updated_at }}
                    </span>
                    <v-spacer></v-spacer>
                    <router-link
                      :to="{
                        name: 'update-draft',
                        params: { link: draft.link },
                      }"
                    >
                      <v-icon class="info--text ml-3">
                        mdi-file-document-edit</v-icon
                      >
                    </router-link>
                    <v-icon
                      @click="OpendeleteDialogDraft(index, draft.link)"
                      class="red--text text--lighten-1"
                    >
                      mdi-delete
                    </v-icon>
                  </div>
                  <v-divider class="mt-5"></v-divider>
                </div>

                <!---------------------------------->
                <v-dialog v-model="deleteDialogDraft" width="500" persistent>
                  <v-card>
                    <v-card-title>
                      آیا از حذف پیش نویس مطمئن هستید؟
                    </v-card-title>
                    <v-card-actions>
                      <v-btn color="error" @click="deleteDraft">بلی</v-btn>
                      <v-btn @click="cancelDeleteDraft">بستن</v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
                <!---------------------->
              </v-tab-item>
              <v-tab-item value="posts">
                <div class="my-5" v-for="(post, index) in posts" :key="post.id">
                  <router-link
                    :to="{ name: 'postShow', params: { slug: post.slug } }"
                    class="title"
                  >
                    {{ post.title || "بدون عنوان" }}
                  </router-link>
                  <div class="d-flex flex-row">
                    <span class="body-2 gray--text"
                      >آخرین ویرایش:
                      {{ post.updated_at }}
                    </span>
                    <v-spacer></v-spacer>
                    <router-link
                      :to="{
                        name: 'edit-post',
                        params: { slug: post.slug },
                      }"
                    >
                      <v-icon class="info--text ml-3">
                        mdi-file-document-edit</v-icon
                      >
                    </router-link>
                    <v-icon
                      @click="OpendeleteDialogPost(index, post.slug)"
                      class="red--text text--lighten-1"
                    >
                      mdi-delete
                    </v-icon>
                  </div>
                  <v-divider class="mt-5"></v-divider>
                </div>
                <!---------------------------------->
                <v-dialog v-model="deleteDialogPost" width="500" persistent>
                  <v-card>
                    <v-card-title> آیا از حذف نوشته مطمئن هستید؟ </v-card-title>
                    <v-card-actions>
                      <v-btn color="error" @click="deletePost">بلی</v-btn>
                      <v-btn @click="cancelDeletePost">بستن</v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
                <!---------------------->
              </v-tab-item>
            </v-tabs-items>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </div>
</template>

<script>
import { computed, reactive, toRefs } from "@vue/composition-api";
import { mapActions, mapState } from "vuex";
export default {
  metaInfo: {
    title: "پست های من",
  },
  setup(props, context) {
    const data = reactive({
      menu: null,
      deleteDialogDraft: false,
      deletableDraft: {},
      deleteDialogPost: false,
      deletablePost: {},
      drafts: computed(() => context.root.$store.state.draft.drafts),
      drafts_count: computed(
        () => context.root.$store.state.draft.drafts_count
      ),
      posts: computed(() => context.root.$store.state.post.posts),
      posts_count: computed(() => context.root.$store.state.post.posts_count),
    });

    const OpendeleteDialogDraft = (index, link) => {
      data.deleteDialogDraft = true;
      data.deletableDraft = { index, link };
    };
    const cancelDeleteDraft = () => {
      data.deleteDialogDraft = false;
      data.deletableDraft = {};
    };
    const deleteDraft = () => {
      context.root.$store.dispatch("draft/deleteDraft", data.deletableDraft);
      data.deleteDialogDraft = false;
      data.deletableDraft = {};
    };
    const OpendeleteDialogPost = (index, slug) => {
      data.deleteDialogPost = true;
      data.deletablePost = { index, slug };
    };
    const cancelDeletePost = () => {
      data.deleteDialogPost = false;
      data.deletablePost = {};
    };
    const deletePost = () => {
      context.root.$store.dispatch("post/deletePost", data.deletablePost);
      data.deleteDialogPost = false;
      data.deletablePost = {};
    };

    const fetchAllDraft = () =>
      context.root.$store.dispatch("draft/fetchAllDraft");
    const fetchAllPost = () =>
      context.root.$store.dispatch("post/fetchAllPost");

    fetchAllDraft();

    return {
      ...toRefs(data),
      OpendeleteDialogDraft,
      cancelDeleteDraft,
      deleteDraft,
      OpendeleteDialogPost,
      cancelDeletePost,
      deletePost,
      fetchAllDraft,
      fetchAllPost,
    };
    // with toRef har item data ra be ref tabdil mikonim ta niaz nabashad dar code ha benvisim: data.menu
  },
};
</script>

<style>
</style>
