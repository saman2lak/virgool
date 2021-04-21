<template>
  <div>
    <v-app-bar flat app>
      <v-container>
        <v-row>
          <v-col cols="auto">
            <span class="title blue--text d-flex flex-row align-center">
              <v-app-bar-nav-icon
                @click="drawer = !drawer"
                class="focus:tw_outline-none"
              ></v-app-bar-nav-icon>

              <v-img src="/image/logo.png" width="50px" class="" />
              <span class="hidden-sm-and-down">ویرگول</span>
            </span>
          </v-col>
          <v-spacer></v-spacer>
          <v-col cols="auto" class="tw_mt-2 sm:tw_mt-0">
            <a href="">خروج</a>
          </v-col>
        </v-row>
      </v-container>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" app absolute right>
      <v-list dense>
        <v-list-item two-line>
          <v-list-item-avatar>
            <v-avatar>
              <v-img src="/image/avatar.jpg"></v-img>
            </v-avatar>
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title class="body-1">نام کاربر</v-list-item-title>
            <v-list-item-subtitle>نقش کاربر</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
        <v-divider></v-divider>
        <v-list-item v-for="item in items" :key="item.title" link>
          <v-list-item-content>
            <v-list-item-title class="body-1">{{ item }}</v-list-item-title>
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
export default {
  data() {
    return {
      drawer: true,
      searchBtn: false,
      items: ["داشبورد"],
    };
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

