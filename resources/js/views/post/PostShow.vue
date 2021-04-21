<template>
  <div>
    <v-main>
      <v-container v-if="post.user">
        <v-row class="d-flex justify-center">
          <v-col cols="12" md="8">
            <v-list-item>
              <v-list-item-avatar size="80px">
                <v-avatar size="80px"
                  ><v-img :src="post.user.profile_src"></v-img
                ></v-avatar>
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title class="font-weight-bold">
                  {{ post.user.name }}
                  <v-btn
                    class="darken-2 px-5 mr-3 mb-3"
                    :color="post.user.is_follows ? 'info' : 'grey'"
                    rounded
                    dark
                    small
                    v-if="
                      $store.state.user.isLoggedIn &&
                      post.user.id != $store.state.user.user.id
                    "
                    @click="followUser()"
                  >
                    {{ post.user.is_follows ? "دنبال شد" : "دنبال کردن" }}
                  </v-btn>
                </v-list-item-title>
                <v-list-item-subtitle class="mb-3">
                  {{ post.user.bio }}
                </v-list-item-subtitle>
                <v-list-item-subtitle class="blue-grey--text darken-4">
                  {{ moment(post.created_at).fromNow() }} | زمان تقریبی مطالعه :
                  {{ post.min_read }} دقیقه
                </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <div class="mt-12">
              <span
                class="headline font-weight-bold"
                v-html="post.title"
              ></span>
            </div>
            <div class="mt-10">
              <v-img :src="post.image" contain></v-img>
            </div>
            <div
              class="subtitle-1 tw_leading-10 tw_text-justify"
              v-html="post.content"
            ></div>
            <div class="my-10">
              <v-btn
                depressed
                v-for="category in post.categories"
                :key="category.slug"
                class="mr-3 mt-2"
                :to="{ name: 'post-category', params: { slug: category.slug } }"
                >{{ category.title }}</v-btn
              >
            </div>

            <div class="d-flex flex-row align-center mb-3">
              <span
                class="ml-5 tw_cursor-pointer"
                v-if="$store.state.user.isLoggedIn"
                @click="bookmarkPost(post)"
              >
                <v-icon>{{
                  post.is_bookmarked ? "mdi-bookmark" : "mdi-bookmark-outline"
                }}</v-icon>
              </span>
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
              <span class="ml-5"
                ><v-icon>mdi-comment-outline</v-icon> {{ post.comments_count }}
              </span>
              <v-spacer></v-spacer>
              <span><v-icon>mdi-telegram</v-icon></span>
              <span><v-icon>mdi-twitter</v-icon></span>
              <v-btn
                outlined
                class="mr-3"
                v-clipboard:copy="short_link"
                v-clipboard:success="copy_short_link"
                :disabled="short_link == 'کپی شد!!!'"
                rounded
                >{{ short_link }}</v-btn
              >
            </div>

            <v-divider></v-divider>

            <div class="my-3">
              <p class="font-weight-bold">شاید از این نوشته خوشتان بیاید:</p>
              <v-container fluid>
                <v-row>
                  <v-col
                    cols="12"
                    md="4"
                    v-for="relPost in relatedPost"
                    :key="relPost.id"
                  >
                    <v-card>
                      <router-link
                        :to="{
                          name: 'postShow',
                          params: { slug: relPost.slug },
                        }"
                      >
                        <v-img :src="relPost.image"></v-img>
                      </router-link>
                      <v-card-title>
                        <router-link
                          :to="{
                            name: 'postShow',
                            params: { slug: relPost.slug },
                          }"
                        >
                          {{ relPost.title }}
                        </router-link>
                      </v-card-title>
                      <v-card-actions>
                        <v-list-item class="pa-0">
                          <v-list-item-avatar>
                            <router-link
                              :to="{
                                name: 'UserPosts',
                                params: { username: relPost.user.username },
                              }"
                            >
                              <v-avatar>
                                <v-img :src="relPost.user.profile_src"></v-img>
                              </v-avatar>
                            </router-link>
                          </v-list-item-avatar>
                          <v-list-item-content>
                            <v-list-item-title
                              class="body-2 font-weight-bold d-inline-block text-truncate"
                              style="
                                max-width: 100px;
                                font-size: 13px !important;
                              "
                            >
                              <router-link
                                :to="{
                                  name: 'UserPosts',
                                  params: { username: relPost.user.username },
                                }"
                              >
                                {{ relPost.user.name }}
                              </router-link>
                            </v-list-item-title>
                            <v-list-item-subtitle
                              class="caption blue-grey--text darken-4"
                            >
                              {{ moment(relPost.created_at).fromNow() }}
                            </v-list-item-subtitle>
                          </v-list-item-content>
                        </v-list-item>
                        <v-spacer></v-spacer>

                        <v-icon
                          v-if="$store.state.user.isLoggedIn"
                          @click="bookmarkPost(relPost)"
                        >
                          {{
                            relPost.is_bookmarked
                              ? "mdi-bookmark"
                              : "mdi-bookmark-outline"
                          }}</v-icon
                        >
                      </v-card-actions>
                    </v-card>
                  </v-col>
                </v-row>
              </v-container>
            </div>
            <div>
              <span>دیدگاه ها</span>
              <template v-if="$store.state.user.isLoggedIn">
                <v-textarea
                  prepend-icon="mdi-account-circle-outline"
                  label="نظر خود را بنویسید..."
                  outlined
                  v-model="comment.content"
                ></v-textarea>
                <div class="d-flex flex-row">
                  <v-spacer></v-spacer>
                  <v-btn
                    @click="saveComment"
                    color="success"
                    large
                    rounded
                    class="mb-10"
                  >
                    ارسال دیدگاه
                  </v-btn>
                </div>
              </template>
              <template v-else>
                <div>برای ارسال دیدگاه وارد سایت شوید</div>
              </template>
            </div>

            <!--- Comment Cards------->
            <post-comments
              v-for="comment in post.parent_comments"
              :key="comment.id"
              :data="comment"
            />
            <!-- -------------------->
          </v-col>
        </v-row>
      </v-container>
      <v-snackbar v-model="error.show" color="error">
        {{ error.msg }}
      </v-snackbar>
    </v-main>
  </div>
</template>

<script>
import { ref } from "@vue/composition-api";
import moment from "moment-jalaali";
import PostComments from "./PostComments.vue";
import EventBus from "../../EventBus";

moment.loadPersian({
  usePersianDigits: true,
});

export default {
  components: { PostComments },
  metaInfo() {
    return {
      title: this.post.title,
      meta: [{ name: "description", content: this.post.desc }],
    };
  },
  setup(props, context) {
    const post = ref({});
    const relatedPost = ref({});
    const short_link = ref("vrgl.io/skhn4j");
    const app_name = ref(process.env.MIX_APP_URL);
    const comment = ref({
      content: "",
      post_id: post.value.id,
    });
    const error = ref({
      show: false,
      msg: null,
    });

    axios
      .get(`/api/posts/${context.root.$route.params.slug}`)
      .then((res) => {
        post.value = res.data.post;
        relatedPost.value = res.data.relatedPosts;
        short_link.value = app_name.value + `/link/${res.data.post.short_link}`;
        comment.value.post_id = res.data.post.id;

        Echo.channel(`comment_${res.data.post.id}`).listen(
          ".comment.created",
          ({ comment }) => {
            post.value.parent_comments.push(comment);
            EventBus.$emit("comment_created");
          }
        );

        Echo.channel(`comment_${res.data.post.id}`).listen(
          "CommentDeleteEvent",
          ({ comment }) => {
            post.value.parent_comments = post.value.parent_comments.filter(
              (c) => c.id != comment.id
            );
            EventBus.$emit("comment_deleted");
          }
        );
      })
      .catch((err) => {
        if (err.response.status === 404) {
          context.root.$router.push({ name: "notfound" });
        }
        if (err.response.status === 403) {
          context.root.$router.push({ name: "accessdenied" });
        }
      });

    const copy_short_link = () => {
      const link = short_link.value;
      short_link.value = "کپی شد!!!";
      setTimeout(() => {
        short_link.value = link;
      }, 2000);
    };

    const saveComment = () => {
      axios
        .post(`/api/comments/${post.value.slug}`, comment.value)
        .then((res) => {
          comment.value.content = null;
        })
        .catch((err) => {
          showErrorSnackbar(err.response.data.errors.content[0]);
        });
    };

    // Created
    EventBus.$on("error", (msg) => {
      showErrorSnackbar(msg);
    });

    EventBus.$on("comment_created", () => {
      post.value.comments_count++;
    });

    EventBus.$on("comment_deleted", () => {
      post.value.comments_count--;
    });

    const showErrorSnackbar = (msg) => {
      error.value.msg = msg;
      error.value.show = true;
    };

    const bookmarkPost = (postModel) => {
      postModel.is_bookmarked = !postModel.is_bookmarked;
      let requestType = postModel.is_bookmarked ? "post" : "delete";
      axios[requestType](`/api/bookmarks/${postModel.slug}`);
    };

    const likePost = () => {
      post.value.is_liked ? post.value.likes_count-- : post.value.likes_count++;
      post.value.is_liked = !post.value.is_liked;
      let requestType = post.value.is_liked ? "post" : "delete";
      axios[requestType](`/api/likes/${post.value.slug}`);
    };

    const followUser = () => {
      axios.post(`/api/follows/${post.value.user.username}`).then(() => {
        post.value.user.is_follows = !post.value.user.is_follows;
      });
    };

    axios.get(`/api/notification`);

    return {
      post,
      relatedPost,
      moment,
      short_link,
      copy_short_link,
      comment,
      saveComment,
      error,
      showErrorSnackbar,
      bookmarkPost,
      likePost,
      followUser,
    };
  },
};
</script>

<style>
</style>
