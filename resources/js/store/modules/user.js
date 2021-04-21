import axios from "axios";

export const namespaced = true;

export const state = {
    user: window.user,
    isLoggedIn: !!window.user
};

export const mutations = {
    LOGOUT(state) {
        state.isLoggedIn = false;
        state.user = null;
    },
    LOGIN(state, payload) {
        state.isLoggedIn = true;
        state.user = {
            name: payload.data.name,
            isVerify: payload.data.email_verified_at === null ? 0 : 1
        };
    },
    REGISTER(state, payload) {
        state.isLoggedIn = true;
        state.user = {
            name: payload.data.name,
            isVerify: 0
        };
    },
    RESET_PASSWORD(state, payload) {
        state.isLoggedIn = true;
        state.user = {
            name: payload.name
        };
    },
    SET_NAME(state, name) {
        state.user.name = name;
    }
};

export const actions = {
    logout(context) {
        return axios.post("/logout").then(() => {
            context.commit("LOGOUT");
        });
    },
    login(context, form) {
        return axios.post("/login", form).then(res => {
            context.commit("LOGIN", res.data);
        });
    },
    register(context, form) {
        return axios.post("/register", form).then(res => {
            context.commit("REGISTER", res.data);
        });
    },
    resetPassword(context, form) {
        return axios.post("/api/reset-password", form).then(async () => {
            axios.post("/login", form).then(async res1 => {
                context.commit("LOGIN", res1.data);
                let res = await axios.get("/api/me");
                context.commit("RESET_PASSWORD", res.data);
            });
        });
    },
    sendEmail(context, form) {
        return axios.post("/api/forgot-password", form);
    },
    setName(context, name) {
        context.commit("SET_NAME", name);
    }
};
