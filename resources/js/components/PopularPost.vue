<template>
  <v-banner sticky>
    <v-list>
      <v-subheader class="font-weight-black black--text"
        >محبوبترین نوشته ها</v-subheader
      >
      <v-list-item-group>
        <v-list-item v-for="item in [1, 2, 3, 4]" :key="item">
          <v-list-item-icon class="ml-3">
            <v-avatar>
              <v-img src="/image/avatar.jpg"></v-img>
            </v-avatar>
          </v-list-item-icon>
          <v-list-item-content>
            <span
              class="body-2 text-truncate gray--text darken-4 font-weight-medium"
              >مدیریت زمان با جیمیل یا چطوری از جیمیل بهتر استفاده کنیم
            </span>
            <span class="caption gray--text">شایان شلیله </span>
          </v-list-item-content>
        </v-list-item>
      </v-list-item-group>
    </v-list>
  </v-banner>
</template>

<script>
export default {};
</script>

<style>
</style>
