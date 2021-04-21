import Vue from "vue";
import VueRouter from "vue-router";
import { store } from "../store/index";
import routes from "./routes";
Vue.use(VueRouter);

const router = new VueRouter({
    mode: "history",
    routes
});

router.beforeEach((to, from, next) => {
    if (to.meta.guest) {
        if (store.state.user.isLoggedIn) {
            next("/");
        }
    }
    if (to.meta.auth) {
        if (!store.state.user.isLoggedIn) {
            next({ name: "login" });
        }
    }
    if (to.meta.verified) {
        if (store.state.user.user.isVerify === 0) {
            next({ name: "verify-email" });
        }
    }
    next();
});

export default router;
