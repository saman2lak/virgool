<template>
  <div class="mt-5">
    <v-card class="mx-auto">
      <div class="d-flex flex-row">
        <v-list-item>
          <v-list-item-avatar>
            <v-img :src="comment.user.profile_src"></v-img>
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title>{{ comment.user.name }}</v-list-item-title>
            <v-list-item-subtitle>{{
              moment(comment.created_at).fromNow()
            }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
        <v-spacer></v-spacer>
        <v-icon class="pl-5" v-if="OnlyShowToCurrentUser" @click="deleteComment"
          >mdi-delete</v-icon
        >
        <v-icon
          class="pl-5"
          v-if="OnlyShowToCurrentUser"
          @click="showUpdateTextArea"
          >mdi-file-document-edit</v-icon
        >
        <v-icon
          class="pl-5"
          v-if="$store.state.user.isLoggedIn"
          @click="showReplay = !showReplay"
          >mdi-undo</v-icon
        >
      </div>
      <v-card-text>{{ comment.content }}</v-card-text>
    </v-card>

    <!-- ------------ -->
    <div class="mt-6" v-if="showReplay || showUpdateReplay">
      <span>پاسخ ها</span>
      <v-textarea
        prepend-icon="mdi-account-circle-outline"
        label="پاسخ خود را بنویسید..."
        outlined
        v-model="replay.content"
      ></v-textarea>
      <div class="d-flex flex-row">
        <v-spacer></v-spacer>
        <v-btn @click="saveComment" color="success" large rounded class="mb-10">
          ارسال پاسخ
        </v-btn>
        <v-btn @click="cancelReplay" color="info" large rounded class="mr-3"
          >بستن</v-btn
        >
      </div>
    </div>
    <!-- ------------ -->
    <post-comments
      v-for="commentData in comment.replies"
      :key="commentData.id"
      :data="commentData"
      class="mr-5"
    />
  </div>
</template>

<script>
import moment from "moment-jalaali";
import { computed, ref } from "@vue/composition-api";
import EventBus from "../../EventBus";
export default {
  name: "PostComments",
  props: {
    data: {
      type: Object,
    },
  },
  setup(props, context) {
    const showReplay = ref(false);
    const showUpdateReplay = ref(false);
    const replay = ref({
      content: null,
      comment_id: props.data.id,
      post_id: props.data.post_id,
    });

    const comment = ref(props.data);

    const cancelReplay = () => {
      replay.value.content = null;
      showReplay.value = false;
      showUpdateReplay.value = false;
    };

    const saveComment = () => {
      return showReplay.value ? saveReplay() : updateReplay();
    };

    const saveReplay = () => {
      axios
        .post(`/api/replies/${context.root.$route.params.slug}`, replay.value)
        .then((res) => {
          cancelReplay();
        })
        .catch((err) => {
          EventBus.$emit("error", err.response.data.errors.content[0]);
        });
    };
    const updateReplay = () => {
      axios
        .patch(`/api/comments/${comment.value.id}`, replay.value)
        .then((res) => {
          cancelReplay();
          comment.value.content = res.data.data.content;
        })
        .catch((err) => {
          EventBus.$emit("error", err.response.data.errors.content[0]);
        });
    };

    const OnlyShowToCurrentUser = computed(() => {
      return (
        context.root.$store.state.user.isLoggedIn &&
        context.root.$store.state.user.user.id == comment.value.user_id
      );
    });

    const deleteComment = () => {
      axios.delete(`/api/comments/${comment.value.id}`);
    };

    const showUpdateTextArea = () => {
      showUpdateReplay.value = true;
      replay.value.content = comment.value.content;
    };

    Echo.channel(`replay_${props.data.id}`).listen(
      ".replay.created",
      ({ replay }) => {
        comment.value.replies.push(replay);
        EventBus.$emit("comment_created");
      }
    );

    Echo.channel(`replay_${props.data.id}`).listen(
      "CommentDeleteEvent",
      (event) => {
        post.value.replies = post.value.replies.filter(
          (c) => c.id != event.comment.id
        );
        EventBus.$emit("comment_deleted");
      }
    );

    return {
      moment,
      showReplay,
      replay,
      saveComment,
      saveReplay,
      updateReplay,
      cancelReplay,
      OnlyShowToCurrentUser,
      deleteComment,
      showUpdateReplay,
      showUpdateTextArea,
      comment,
    };
  },
};
</script>

<style>
</style>
