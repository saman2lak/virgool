import Vue from "vue";
import Vuex from "vuex";
import * as user from "./modules/user";
import * as draft from "./modules/draft";
import * as post from "./modules/post";
import * as category from "./modules/category";
import * as notification from "./modules/notification";
Vue.use(Vuex);

export const store = new Vuex.Store({
    modules: {
        user,
        draft,
        post,
        category,
        notification
    }
});
