<template>
  <v-system-bar class="d-flex justify-center" height="50px">
    <span
      >شما هنوز ایمیل خود را تایید نکرده اید لطفا به ایمیل خود مراجعه
      نمایید</span
    >
    <span class="mr-5 success--text" v-if="sended"
      >ایمیل با موفقیت ارسال شد</span
    >
    <v-btn small outlined rounded class="mr-5" @click="ResendVerify" v-else
      >ارسال مجدد ایمیل</v-btn
    >
  </v-system-bar>
</template>

<script>
import { ref } from "@vue/composition-api";
export default {
  setup() {
    const sended = ref(false);

    function ResendVerify() {
      axios
        .post("/api/email/verification-notification")
        .then(() => (sended.value = true));
    }

    return {
      sended,
      ResendVerify,
    };
  },
};
</script>

<style>
</style>
