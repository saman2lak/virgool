<template>
  <v-main>
    <v-container v-if="posts.data">
      <v-row class="d-flex justify-center">
        <v-col cols="8" class="d-flex">
          <div class="d-flex">
            <span>نوشته های مربوط به دسته بندی:</span>
            <span>{{ category.title }}</span>
          </div>
          <v-spacer></v-spacer>
          <div class="d-flex">
            <span>تعداد نوشته ها:</span>
            <span>{{ category.posts_count }}</span>
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
              پستی در این دسته بندی وجود ندارد.
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
      title: "دسته بندی :" + this.category.title,
    };
  },
  setup(props, context) {
    const posts = ref({});
    const category = ref({});

    const fetchNextPosts = () => {
      axios.get(posts.value.next_page_url).then((res) => {
        posts.value.data.push(...res.data.post.data); // 3 ta noghte gozashtim ta arraye ro be object tabdil konim
        posts.value.next_page_url = res.data.post.next_page_url;
      });
    };

    axios
      .get(`/api/posts/category/${context.root.$route.params.slug}`)
      .then((res) => {
        category.value = res.data.category;
        posts.value = res.data.post;
      });

    return {
      posts,
      category,
      fetchNextPosts,
    };
  },
};
</script>

<style>
</style>
