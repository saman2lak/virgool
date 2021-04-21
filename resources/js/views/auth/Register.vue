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
          <v-form class="tw_w-screen mx-5" ref="registerForm">
            <span>ایجاد حساب کاربری</span>

            <v-text-field
              outlined
              rounded
              placeholder="ایمیل"
              class="mt-8"
              v-model="form.email"
              :rules="emailRules"
              :error-messages="errors.email"
            >
            </v-text-field>

            <v-text-field
              outlined
              rounded
              placeholder="رمز عبور"
              v-model="form.password"
              :rules="passRules"
              :error-messages="errors.password"
            >
            </v-text-field>

            <div class="d-flex justify-end">
              <v-btn color="info" rounded @click="register">
                <template v-if="loading">
                  <v-progress-circular
                    color="white"
                    :indeterminate="true"
                  ></v-progress-circular>
                </template>
                <template v-else>
                  ایجاد حساب کاربری
                  <v-icon>mdi-chevron-left</v-icon>
                </template>
              </v-btn>
            </div>

            <div
              class="d-flex flex-column justify-center align-center clear-both mt-10"
            >
              <span class="red--text">ورود با اکانت گوگل</span>
              <router-link :to="{ name: 'login' }" class="blue--text mt-5"
                >قبلا عضو شده اید ؟ رفتن به صفحه ورود!</router-link
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
import { RegisterModule } from "../../modules/auth/RegisterModule";
import { Required, VerifyEmail, ValueLength } from "../../modules/rules/rules";
export default {
  metaInfo: {
    title: "ثبت نام   ",
  },
  setup() {
    const emailRules = ref([
      Required("ایمیل الزامی است"),
      VerifyEmail("ایمیل صحیح وارد نمایید"),
      ValueLength(7, "ایمیل"),
    ]);
    const passRules = ref([
      Required("رمزعبور الزامی است"),
      ValueLength(8, "رمز عبور"),
    ]);

    return {
      ...RegisterModule(),
      emailRules,
      passRules,
    };
  },
};
</script>

<style>
.gradiant-bg {
  background-image: linear-gradient(to bottom right, #054592 0, #1897d4);
}
</style>
