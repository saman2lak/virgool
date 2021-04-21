<template>
  <div>
    <v-container fluid>
      <v-row :class="$vuetify.breakpoint.mdAndUp ? 'tw_h-screen' : 'tw_h-5'">
        <v-col
          cols="12"
          md="8"
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
          <span class="body-1 mt-3 hidden-sm-and-down">
            همین حالا حساب کاربری خودت را بساز و دوران جدید وبلاگ نویسی را شروع
            کن.
          </span>
        </v-col>
        <v-col cols="12" md="4" class="d-flex align-center">
          <v-form class="tw_w-screen mx-5" ref="LoginForm">
            <span>ورود به حساب کاربری</span>
            <v-text-field
              outlined
              rounded
              placeholder="ایمیل"
              class="mt-8"
              v-model="form.email"
              :rules="UsernameRules"
              :error-messages="errors.email"
            ></v-text-field>

            <v-text-field
              outlined
              rounded
              type="password"
              placeholder="رمز عبور"
              v-model="form.password"
              :rules="PasswordRules"
              :error-messages="errors.password"
            ></v-text-field>

            <div class="d-flex justify-end">
              <template v-if="loading">
                <v-btn color="info" rounded @click="login">
                  <v-progress-circular
                    color="white"
                    :indeterminate="true"
                  ></v-progress-circular>

                  <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
              </template>
              <template v-else>
                <v-btn color="info" rounded @click="login">
                  ورود به حساب کاربری

                  <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
              </template>
            </div>

            <div
              class="d-flex flex-column justify-center align-center clear-both mt-10"
            >
              <router-link :to="{ name: 'reset-email-password' }" class="mb-5"
                >فراموشی رمز عبور</router-link
              >
              <span class="red--text">ورود با اکانت گوگل</span>
              <router-link :to="{ name: 'register' }" class="blue--text mt-5">
                عضو نیستید ؟ رفتن به صفحه ثبت نام!</router-link
              >
            </div>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { ref } from "@vue/composition-api";
import { Required } from "../../modules/rules/rules";
import { LoginModule } from "../../modules/auth/LoginModule";
export default {
  metaInfo: {
    title: " ورود به سایت   ",
  },
  setup() {
    const UsernameRules = ref([Required("نام کاربری الزامی است")]);
    const PasswordRules = ref([Required("رمز عبور الزامی است")]);

    return {
      UsernameRules,
      PasswordRules,
      ...LoginModule(),
    };
  },
};
</script>

<style>
.gradiant-bg {
  background-image: linear-gradient(to bottom right, #054592 0, #1897d4);
}
</style>
