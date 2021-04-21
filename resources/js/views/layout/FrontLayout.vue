<template>
  <div>
    <VerifyBanner v-if="user && user.isVerify == 0"></VerifyBanner>
    <v-app-bar flat :height="$vuetify.breakpoint.smAndDown ? 80 : 160">
      <v-container fluid>
        <v-row>
          <v-container class="pa-4">
            <v-row>
              <v-col cols="auto">
                <span class="title blue--text d-flex flex-row">
                  <router-link :to="{ name: 'home' }">
                    <v-img src="/image/logo.png" width="50px" class="" />
                    <span
                      class="tw_absolute tw_top-12 tw_mr-11 hidden-sm-and-down"
                      >ویرگول</span
                    >
                  </router-link>

                  <v-app-bar-nav-icon
                    @click="drawer = !drawer"
                    class="hidden-md-and-up focus:tw_outline-none"
                  ></v-app-bar-nav-icon>
                </span>
              </v-col>
              <v-spacer></v-spacer>
              <v-col cols="auto" class="tw_mt-2 sm:tw_mt-0">
                <v-menu
                  top
                  absolute
                  min-width="100%"
                  :close-on-content-click="false"
                  v-model="searchBtn"
                >
                  <template v-slot:activator="{ on }">
                    <v-btn
                      plain
                      class="focus:tw_outline-none"
                      @click="searchBtn = true"
                    >
                      <v-icon v-on="on">mdi-magnify</v-icon>
                    </v-btn>
                  </template>
                  <v-list class="d-flex flex-row align-center">
                    <v-text-field
                      class="mr-10"
                      placeholder="در بین مقالات جستجو کنید..."
                    ></v-text-field>
                    <v-btn class="ml-10" plain @click="searchBtn = false"
                      >بستن</v-btn
                    >
                  </v-list>
                </v-menu>

                <template v-if="!auth">
                  <v-btn
                    plain
                    color="primary"
                    class="pa-0"
                    :to="{ name: 'login' }"
                    >ورود</v-btn
                  >
                  <v-btn color="primary" class="pa-0" :to="{ name: 'register' }"
                    >ثبت نام</v-btn
                  >
                </template>
                <template v-else>
                  <v-btn text @click="NotificationDrawer = !NotificationDrawer">
                    <v-badge
                      right
                      overlap
                      :content="$store.state.notification.notifyCount"
                    >
                      <v-icon>mdi-bell</v-icon>
                    </v-badge>
                  </v-btn>
                  <v-menu offset-y transition="slide-y-transition">
                    <template v-slot:activator="{ on }">
                      <v-btn v-on="on" fab class="mr-3">
                        <v-avatar>
                          <v-img
                            :src="$store.state.user.user.profile_src"
                            max-width="50px"
                            max-height="50px"
                          ></v-img>
                        </v-avatar>
                      </v-btn>
                    </template>
                    <v-list width="250px">
                      <v-list-item two-line :to="{ name: 'profile' }">
                        <v-list-item-content>
                          <v-list-item-title>
                            {{ name }}
                          </v-list-item-title>
                          <v-list-item-subtitle
                            >پروفایل من</v-list-item-subtitle
                          >
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item :to="{ name: 'create-post' }">
                        افزودن پست جدید
                      </v-list-item>
                      <v-list-item :to="{ name: 'myposts' }">
                        پست های من
                      </v-list-item>
                      <v-list-item :to="{ name: 'liked-posts' }">
                        پست هایی که لایک کردید
                      </v-list-item>
                      <v-list-item :to="{ name: 'bookmarked-posts' }">
                        پست هایی که بوکمارک کردید
                      </v-list-item>
                      <v-list-item @click.prevent="logout">
                        خروج از حساب کاربری
                      </v-list-item>
                      <v-divider></v-divider>
                      <v-list-item @click.prevent="nightMode">
                        <template v-if="$vuetify.theme.dark">
                          حالت روز</template
                        >
                        <template v-else>حالت شب</template>
                      </v-list-item>
                    </v-list>
                  </v-menu>
                </template>
              </v-col>
            </v-row>
          </v-container>
        </v-row>
        <v-row v-if="$vuetify.breakpoint.mdAndUp">
          <v-col cols="12" class="blue darken-2 white--text">
            <v-container class="pa-0">
              <v-row>
                <v-col cols="12">
                  <span class="pl-3 body-2 tw_cursor-pointer"
                    >جدیدترین پست ها</span
                  >
                  <span class="pl-3 body-2 tw_cursor-pointer"
                    >پست های دوستان</span
                  >
                  <v-hover
                    v-for="item in $store.state.category.categories"
                    :key="item.id"
                    v-slot:default="{ hover }"
                  >
                    <router-link
                      class="pl-3 body-1 caption tw_cursor-pointer text--lighten-4"
                      :class="hover ? 'white--text' : 'blue--text'"
                      :to="{
                        name: 'post-category',
                        params: { slug: item.slug },
                      }"
                    >
                      {{ item.title }}
                    </router-link>
                  </v-hover>
                </v-col>
              </v-row>
            </v-container>
          </v-col>
        </v-row>
      </v-container>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" app absolute right temporary>
      <v-list dense>
        <v-list-item
          v-for="item in $store.state.category.categories"
          :key="item.id"
          link
        >
          <v-list-item-content>
            <v-list-item-title>{{ item }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <!---------- NotificationDrawer ---------->
    <v-navigation-drawer
      v-model="NotificationDrawer"
      app
      absolute
      left
      temporary
    >
      <v-list dense>
        <v-list-item
          v-for="item in $store.state.notification.NotifyItems"
          :key="item.id"
          link
        >
          <v-list-item-content>
            <a
              class="tw_p-3"
              :class="!!item.read_at ? 'grey white--text' : 'blue white--text'"
              :href="item.data.link"
              @click.prevent="readNotification(item)"
              >{{ item.data.text }}</a
            >
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <!-- Show Children of this rout in this Area -->
    <router-view></router-view>
    <!-- end Child -->
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import VerifyBanner from "../../components/VerifyBanner";
export default {
  components: {
    VerifyBanner,
  },
  metaInfo: {
    title: "صفحه اصلی",
    titleTemplate: "%s | ویرگول",
  },
  data() {
    return {
      drawer: false,
      NotificationDrawer: false,
      searchBtn: false,
    };
  },
  created() {
    this.$store.dispatch("notification/getNotifications");
    this.$store.dispatch("category/getNavbarCategories");
  },
  computed: {
    ...mapState({
      auth: (state) => state.user.isLoggedIn,
      name: (state) => state.user.user.name,
      user: (state) => state.user.user,
    }),
  },
  methods: {
    logout() {
      this.$store.dispatch("user/logout").then(() => {
        this.$router.push({ name: "home" });
      });
    },
    nightMode() {
      this.$vuetify.theme.dark = !this.$vuetify.theme.dark;
      this.$vuetify.theme.dark
        ? localStorage.setItem("isDark", 1)
        : localStorage.removeItem("isDark");
    },
    readNotification(notification) {
      axios.patch(`/api/notification/${notification.id}`).then(() => {
        this.$router.push(notification.data.link);
      });
    },
  },
};
</script>

<style>
.v-toolbar__content {
  padding: 0 !important;
}
.v-btn {
  letter-spacing: 0;
}
.v-input__control .v-input__slot::before {
  border-color: white !important;
}
.v-menu__content {
  border-radius: unset !important;
}
</style>

