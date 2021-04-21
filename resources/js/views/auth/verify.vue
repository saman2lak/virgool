<template>
  <div>
    <v-container fluid>
      <v-row :class="$vuetify.breakpoint.mdAndUp ? 'tw_h-screen' : 'tw_h-5'">
        <v-col
          cols="12"
          md="4"
          class="gradiant-bg d-flex flex-column align-center justify-center white--text"
        >
          <v-img
            src="/image/avatar.jpg"
            max-height="100px"
            class="rounded-xl mb-10"
          ></v-img>
          <span
            class="font-weight-bold"
            :class="$vuetify.breakpoint.mdAndUp ? 'display-1' : 'body-1'"
            >به ویرگول خوش آمدید!</span
          >
          <span class="body-1 mt-3 hidden-sm-and-down tw_text-center">
            همین حالا حساب کاربری خودت را بساز و دوران جدید وبلاگ نویسی را شروع
            کن.
          </span>
        </v-col>
        <v-col
          cols="12"
          md="8"
          class="d-flex flex-column align-center justify-center"
        >
          <p class="blue--text title font-weight-bold">
            اینجا شروع دوران جدید نوشتن شما است.
          </p>
          <p class="blue--text subtitle font-weight-bold">
            برای انجام مراحل ثبت نام لطفا ایمیل خود را چک نموده و روی لینک
            فعالسازی کلیک نمایید.
          </p>
          <v-btn class="mt-12" rounded color="info" :to="{ name: 'home' }"
            >ورود به صفحه اصلی</v-btn
          >
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  metaInfo: {
    title: "تایید ایمیل ",
  },
};
</script>

<style>
.gradiant-bg {
  background-image: linear-gradient(to bottom right, #054592 0, #1897d4);
}
</style>
