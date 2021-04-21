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
            <span>ایمیل خود را برای بازیابی کلمه عبور وارد نمایید :</span>
            <template v-if="!sended">
              <v-text-field
                class="pt-5"
                outlined
                rounded
                placeholder="ایمیل "
                v-model="form.email"
                :rules="UsernameRules"
                :error-messages="errors.email"
              ></v-text-field>

              <div class="d-flex justify-end">
                <template v-if="loading">
                  <v-btn color="info" rounded @click="resetPassword">
                    <v-progress-circular
                      color="white"
                      :indeterminate="true"
                    ></v-progress-circular>

                    <v-icon>mdi-chevron-left</v-icon>
                  </v-btn>
                </template>
                <template v-else>
                  <v-btn color="info" rounded @click="resetPassword">
                    بازیابی رمز عبور

                    <v-icon>mdi-chevron-left</v-icon>
                  </v-btn>
                </template>
              </div>
            </template>
            <template v-else>
              <div class="success--text">
                ایمیل به آدرس نوشته شده ارسال گردید
              </div>
            </template>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { ref } from "@vue/composition-api";
import { Required } from "../../modules/rules/rules";
export default {
  metaInfo: {
    title: "بازیابی رمز عبور ",
  },
  setup(props, context) {
    const ResetPassForm = ref(null);
    const loading = ref(false);
    const sended = ref(false);
    const form = ref({
      email: null,
    });

    const errors = ref({
      email: null,
    });
    const UsernameRules = ref([Required("ایمیل الزامی است")]);

    function resetPassword() {
      if (context.refs.ResetPassForm.validate()) {
        loading.value = true;
        context.root.$store
          .dispatch("user/sendEmail", form.value)
          .then(() => (sended.value = true))
          .catch(
            (err) => (errors.value.email = err.response.data.errors.email[0])
          )
          .finally(() => (loading.value = false));
      }
    }

    return {
      UsernameRules,
      ResetPassForm,
      loading,
      form,
      errors,
      resetPassword,
      sended,
    };
  },
};
</script>

<style>
.gradiant-bg {
  background-image: linear-gradient(to bottom right, #054592 0, #1897d4);
}
</style>
