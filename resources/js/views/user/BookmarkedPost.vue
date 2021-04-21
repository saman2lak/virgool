<template>
  <v-main>
    <v-container v-if="posts.data">
      <v-row class="d-flex justify-center">
        <v-col cols="8" class="d-flex">
          <div class="d-flex">
            <span>نوشته های بوکمارک شده شما:</span>
          </div>
          <v-spacer></v-spacer>
          <div class="d-flex">
            <span>تعداد نوشته های بوکمارک شده:</span>
            <span>{{ user.bookmarks_count }}</span>
          </div>
        </v-col>
      </v-row>
      <v-row class="d-flex justify-center">
        <v-col cols="8">
          <new-post
            class="mt-5"
            v-for="(post, index) in posts.data"
            :key="index"
            :data="post"
          ></new-post>
          <span v-if="!posts.data.length">
            <v-alert border="left" color="blue-grey" dark>
              شما نوشته ای را بوکمارک نکرده اید.
            </v-alert>
          </span>

          <v-btn
            color="info"
            class="mt-10"
            v-if="!!posts.next_page_url"
            @click="fetchNextPosts"
            >صفحه بعدی</v-btn
          >
        </v-col>
      </v-row>
    </v-container>
  </v-main>
</template>

<script>
import NewPost from "../../components/NewPost";
import { ref } from "@vue/composition-api";
import metaInfo from "vue-meta";
export default {
  name: "PostCategory",
  components: { NewPost },
  metaInfo() {
    return {
      title: "نوشته های بوکمارک شده",
    };
  },
  setup(props, context) {
    const posts = ref({});
    const user = ref({});

    const fetchNextPosts = () => {
      axios.get(posts.value.next_page_url).then((res) => {
        posts.value.data.push(...res.data.post.data); // 3 ta noghte gozashtim ta arraye ro be object tabdil konim
        posts.value.next_page_url = res.data.post.next_page_url;
      });
    };

    axios.get(`/api/bookmarked-post`).then((res) => {
      user.value = res.data.user;
      posts.value = res.data.posts;
    });

    return {
      posts,
      user,
      fetchNextPosts,
    };
  },
};
</script>

<style>
</style>
