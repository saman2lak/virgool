<template>
  <div>
    <v-main>
      <v-container>
        <v-row>
          <v-col
            v-for="(post, i) in posts.data"
            :key="i"
            cols="12"
            sm="6"
            :md="post.col"
          >
            <!-- <VipPost :data="posts"></VipPost> -->
          </v-col>
        </v-row>
      </v-container>
      <v-container>
        <v-row>
          <v-col cols="12" md="8">
            <span class="blue--text darken-4">جدیدترین نوشته ها</span>
            <NewPost
              v-for="post in posts.data"
              :data="post"
              :key="post.id"
              class="mt-5"
            ></NewPost>

            <v-btn
              color="info"
              class="mt-10"
              v-if="!!posts.next_page_url"
              @click="fetchNextPosts"
              >پست های بیشتر</v-btn
            >
          </v-col>
          <v-col cols="12" md="4" class="hidden-sm-and-down">
            <PopularPost></PopularPost>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </div>
</template>

<script>
import VipPost from "./VipPost";
import NewPost from "../components/NewPost";
import PopularPost from "../components/PopularPost";
import { ref } from "@vue/composition-api";

export default {
  components: { VipPost, NewPost, PopularPost },
  metaInfo: {
    title: "صفحه اصلی",
  },
  setup() {
    const posts = ref({});

    axios.get(`/api/home-post`).then((res) => {
      posts.value = res.data.posts;
    });

    const fetchNextPosts = () => {
      axios.get(posts.value.next_page_url).then((res) => {
        posts.value.data.push(...res.data.posts.data); // 3 ta noghte gozashtim ta arraye ro be object tabdil konim
        posts.value.next_page_url = res.data.posts.next_page_url;
      });
    };

    return { posts, fetchNextPosts };
  },
};
</script>

<style>
</style>
