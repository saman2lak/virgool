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
          <v-form class="tw_w-screen mx-5" ref="ResetPassForm">
            <span>رمز عبور جدید خود را وارد نمایید</span>
            <v-text-field
              outlined
              rounded
              placeholder="ایمیل "
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
            <v-text-field
              outlined
              rounded
              type="password"
              placeholder="تایید رمز عبور"
              v-model="form.password_confirmation"
              :rules="PasswordRules"
              :error-messages="errors.password_confirmation"
            ></v-text-field>

            <div class="d-flex justify-end">
              <template v-if="loading">
                <v-btn color="info" rounded @click="changePassword">
                  <v-progress-circular
                    color="white"
                    :indeterminate="true"
                  ></v-progress-circular>

                  <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
              </template>
              <template v-else>
                <v-btn color="info" rounded @click="changePassword">
                  ثبت کلمه عبور

                  <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
              </template>
            </div>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { ref } from "@vue/composition-api";
import { Required, ValueLength } from "../../modules/rules/rules";
export default {
  metaInfo: {
    title: "بازیابی رمز عبور ",
  },
  setup(props, context) {
    const ResetPassForm = ref(null);
    const loading = ref(false);
    const form = ref({
      email: null,
      password: null,
      password_confirmation: null,
      token: context.root.$route.params.token,
    });

    const errors = ref({
      email: null,
      password: null,
      password_confirmation: null,
    });

    const UsernameRules = ref([Required("ایمیل الزامی است")]);

    const PasswordRules = ref([
      Required("رمزعبور الزامی است"),
      ValueLength(8, "رمز عبور"),
    ]);

    function changePassword() {
      if (context.refs.ResetPassForm.validate()) {
        loading.value = true;
        context.root.$store
          .dispatch("user/resetPassword", form.value)
          .then(() => {
            context.root.$router.push({ name: "home" });
          })
          .catch((err) => {
            errors.value.email = err.response.data.errors.email[0];
          })
          .finally(() => (loading.value = false));
      }
    }

    return {
      PasswordRules,
      UsernameRules,
      ResetPassForm,
      loading,
      form,
      errors,
      changePassword,
    };
  },
};
</script>

<style>
.gradiant-bg {
  background-image: linear-gradient(to bottom right, #054592 0, #1897d4);
}
</style>
