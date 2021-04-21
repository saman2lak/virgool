<template>
  <v-container v-if="user">
    <v-row align="center" justify="center" class="d-flex flex-column">
      <v-col cols="12" md="8">
        <v-form ref="form">
          <div class="d-flex flex-row">
            <v-avatar size="80px">
              <v-img
                :src="user.profile_src || '/image/avatar.jpg'"
                @click="$refs.profile.click()"
              ></v-img>
              <input
                type="file"
                style="display: none"
                ref="profile"
                @change="uploadProfile"
              />
            </v-avatar>
            <div class="d-flex flex-column mr-5 tw_w-full">
              <v-text-field
                outlined
                color="deep-purple accent-4"
                placeholder="نام"
                label="نام"
                v-model="user.name"
                :rules="RequiredRules"
              ></v-text-field>
              <v-textarea
                color="deep-purple accent-4"
                label="بیوگرافی"
                outlined
                placeholder="بیوگرافی"
                v-model="user.bio"
              ></v-textarea>
            </div>
          </div>

          <v-divider class="mt-12 mb-7"></v-divider>
          <div>
            <h2>تنظیم حساب کاربری</h2>
            <div class="mt-5 d-flex flex-row">
              <div class="d-flex flex-column">
                <span class="title">نام کاربری</span>
                <div class="d-flex flex-row">
                  <v-btn
                    text
                    rounded
                    small
                    outlined
                    :class="{
                      success: editing.username,
                      'white--text': editing.username,
                    }"
                    class="mt-3"
                    @click="changeEdit('username')"
                  >
                    {{ editing.username ? "ثبت تغییرات " : "ویرایش" }}
                  </v-btn>
                  <v-btn
                    text
                    rounded
                    small
                    outlined
                    class="mt-3 mr-2"
                    @click="cancel('username')"
                    v-show="editing.username"
                  >
                    منصرف شدن
                  </v-btn>
                </div>
              </div>
              <v-spacer></v-spacer>
              <template v-if="!editing.username">
                <span class="mt-4">{{ FullUserName }}</span>
              </template>
              <template v-else>
                <v-text-field
                  outlined
                  class="ltr"
                  :prefix="url"
                  v-model="user.username"
                  color="deep-purple accent-4"
                  :rules="UsernameRules"
                  :error-messages="errors.username"
                ></v-text-field>
              </template>
            </div>
            <v-divider class="mt-5 mb-5"></v-divider>

            <div class="mt-5 d-flex flex-row">
              <div class="d-flex flex-column">
                <span class="title">ایمیل</span>
                <div class="d-flex flex-row">
                  <v-btn
                    text
                    rounded
                    outlined
                    small
                    class="mt-3"
                    :class="{
                      success: editing.email,
                      'white--text': editing.email,
                    }"
                    @click="changeEdit('email')"
                  >
                    {{ editing.email ? "ثبت تغییرات " : "ویرایش" }}
                  </v-btn>
                  <v-btn
                    text
                    rounded
                    small
                    outlined
                    class="mt-3 mr-2"
                    @click="cancel('email')"
                    v-show="editing.email"
                  >
                    منصرف شدن
                  </v-btn>
                </div>
              </div>
              <v-spacer></v-spacer>
              <!-- <span>samanghavami@gmail.com</span> -->
              <template v-if="!editing.email">
                <span class="mt-4">{{ user.email }}</span>
              </template>
              <template v-else>
                <v-text-field
                  outlined
                  class="ltr"
                  prefix="Email :"
                  v-model="user.email"
                  color="deep-purple accent-4"
                  :rules="RequiredRules"
                  :error-messages="errors.email"
                ></v-text-field>
              </template>
            </div>
            <v-divider class="mt-5 mb-5"></v-divider>

            <div class="mt-5 d-flex flex-row">
              <div class="d-flex flex-column">
                <span class="title">رمز عبور</span>
              </div>
              <v-spacer></v-spacer>
              <v-text-field
                outlined
                class="ltr"
                prefix="تغییر کلمه عبور :"
                v-model="user.password"
                color="deep-purple accent-4"
                :rules="LengthRules"
              ></v-text-field>
            </div>
            <v-divider class="mt-5 mb-5"></v-divider>

            <div class="mt-5 d-flex flex-row">
              <div class="d-flex flex-column">
                <span class="title mt-4">ارسال ایمیل پس از فالو</span>
              </div>
              <v-spacer></v-spacer>
              <v-switch inset v-model="user.email_on_follow"></v-switch>
            </div>
            <v-divider class="mt-5 mb-5"></v-divider>

            <div class="mt-5 d-flex flex-row">
              <div class="d-flex flex-column">
                <span class="title mt-4">ارسال ایمیل پس از لایک</span>
              </div>
              <v-spacer></v-spacer>
              <v-switch inset v-model="user.email_on_like"></v-switch>
            </div>
            <v-divider class="mt-5 mb-5"></v-divider>

            <div class="mt-5 d-flex flex-row">
              <div class="d-flex flex-column">
                <span class="title mt-4">ارسال ایمیل پس از ریپلای</span>
              </div>
              <v-spacer></v-spacer>
              <v-switch inset v-model="user.email_on_replay"></v-switch>
            </div>
            <v-divider class="mt-5 mb-5"></v-divider>

            <div class="mt-5 d-flex flex-row mb-10">
              <v-btn rounded color="success" @click="update"
                >ذخیره تغییرات</v-btn
              >
            </div>
          </div>
        </v-form>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { ref } from "@vue/composition-api";
import ProfileModule from "../../modules/user/ProfileModule";
import {
  Required,
  ValueLengthNullable,
  VerifyUsername,
} from "../../modules/rules/rules";
export default {
  metaInfo: {
    title: "پروفایل من",
  },
  setup() {
    const RequiredRules = ref([Required("این فیلد الزامی است")]);
    const LengthRules = ref([ValueLengthNullable(8, "کلمه عبور")]);
    const UsernameRules = ref([
      Required("رمزعبور الزامی است"),
      VerifyUsername(" نام کاربری را در فرمت مناسب وارد نمایید"),
    ]);

    return {
      RequiredRules,
      LengthRules,
      UsernameRules,
      ...ProfileModule(),
    };
  },
};
</script>

<style>
</style>
