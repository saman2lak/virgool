<template>
  <v-main>
    <v-container v-if="posts.data">
      <v-row class="d-flex justify-center">
        <v-col cols="8" class="d-flex">
          <div class="d-flex flex-row align-center">
            <v-avatar size="100">
              <v-img :src="user.profile_src"></v-img>
            </v-avatar>
            <p class="title mr-3">{{ user.name }}</p>
            <v-btn
              class="darken-2 px-5 mr-3 mb-3"
              :color="user.is_follows ? 'info' : 'grey'"
              rounded
              dark
              small
              v-if="
                $store.state.user.isLoggedIn &&
                user.id != $store.state.user.user.id
              "
              @click="followUser()"
            >
              {{ user.is_follows ? "دنبال شد" : "دنبال کردن" }}
            </v-btn>
          </div>
          <v-spacer></v-spacer>
          <div class="mr-3 d-flex flex-row align-center">
            <span>تعداد نوشته ها:</span>
            <span>{{ user.posts_count }}</span>
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
export default {
  name: "UserPosts",
  components: { NewPost },
  metaInfo() {
    return {
      title: `پست های کاربر ${this.user.name}`,
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
    const followUser = () => {
      axios.post(`/api/follows/${user.value.username}`).then(() => {
        user.value.is_follows = !user.value.is_follows;
      });
    };

    axios
      .get(`api/user-post/${context.root.$route.params.username}`)
      .then((res) => {
        user.value = res.data.user;
        posts.value = res.data.post;
      });

    return { posts, user, fetchNextPosts, followUser };
  },
  methods: {},
};
</script>

<style>
</style>
