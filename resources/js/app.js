require("./bootstrap");
import Vue from "vue";
import router from "./router/router";
import vuetify from "./plugins/vuetify";
import VueCompositionAPI from "@vue/composition-api";
import { store } from "./store/index";
import VueMeta from "vue-meta";
import VueClipboard from "vue-clipboard2";

VueClipboard.config.autoSetContainer = true; // add this line
Vue.use(VueClipboard);

Vue.use(VueMeta);
Vue.use(VueCompositionAPI);

Vue.component(
    "app-component",
    require("./components/AppComponent.vue").default
);

new Vue({
    vuetify,
    router,
    store,
    el: "#app"
});
