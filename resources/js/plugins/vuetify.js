import Vue from "vue";
import Vuetify from "vuetify";

Vue.use(Vuetify);

const opts = {
    rtl: true,
    theme: {
        dark: localStorage.getItem("isDark") == 1 ? true : false
    }
};

export default new Vuetify(opts);
