<template>
  <div>
    <v-card class="mt-7 pa-2">
      <div class="d-flex flex-row">
        <v-list-item>
          <v-list-item-avatar>
            <router-link
              :to="{
                name: 'UserPosts',
                params: { username: post.user.username },
              }"
            >
              <v-img :src="post.user.profile_src"></v-img>
            </router-link>
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title>
              <router-link
                :to="{ name: 'postShow', params: { slug: post.slug } }"
              >
                {{ post.title }}
              </router-link>
            </v-list-item-title>
            <v-list-item-subtitle>
              {{ MommentFromNow }} | زمان تقریبی مطالعه :
              {{ post.min_read }} دقیقه
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>

        <v-spacer></v-spacer>
        <span class="caption d-flex">
          <span
            class="d-flex"
            v-for="(category, index) in post.categories"
            :key="index"
          >
            {{ category.title }}
            <span v-if="index != Object.keys(post.categories).length - 1"
              >,</span
            >
          </span>
        </span>
      </div>
      <router-link :to="{ name: 'postShow', params: { slug: post.slug } }">
        <v-img :src="post.image" height="300px" class="my-5"></v-img>
      </router-link>

      <v-card-text v-html="post.desc"> </v-card-text>
      <v-card-actions class="mt-2">
        <span
          class="ml-5 tw_cursor-pointer"
          v-if="$store.state.user.isLoggedIn"
          @click="likePost()"
        >
          <v-icon :class="post.is_liked ? 'red--text' : ''">{{
            post.is_liked ? "mdi-heart" : "mdi-heart-outline"
          }}</v-icon>
          {{ post.likes_count }}
        </span>
        <v-spacer></v-spacer>
        <span
          class="ml-5 tw_cursor-pointer"
          v-if="$store.state.user.isLoggedIn"
          @click="bookmarkPost()"
        >
          <v-icon>{{
            post.is_bookmarked ? "mdi-bookmark" : "mdi-bookmark-outline"
          }}</v-icon>
        </span>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import moment from "moment-jalaali";
import { ref, computed } from "@vue/composition-api";
moment.loadPersian({
  usePersianDigits: true,
});
export default {
  name: "NewPost",
  props: {
    data: {
      type: Object,
      require: true,
    },
  },
  setup(props) {
    const post = ref(props.data);

    const MommentFromNow = computed(() => {
      return moment(post.value.created_at).fromNow();
    });

    const likePost = () => {
      post.value.is_liked ? post.value.likes_count-- : post.value.likes_count++;
      post.value.is_liked = !post.value.is_liked;
      let requestType = post.value.is_liked ? "post" : "delete";
      axios[requestType](`/api/likes/${post.value.slug}`);
    };
    const bookmarkPost = () => {
      post.value.is_bookmarked = !post.value.is_bookmarked;
      let requestType = post.value.is_bookmarked ? "post" : "delete";
      axios[requestType](`/api/bookmarks/${post.value.slug}`);
    };

    return { post, MommentFromNow, likePost, bookmarkPost };
  },
};
</script>

<style>
</style>
