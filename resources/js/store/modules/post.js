export const namespaced = true;

export const state = {
    post: null,
    posts: null,
    posts_count: null
};

export const mutations = {
    SET_POST(state, data) {
        state.post = data;
    },
    SET_POST_COUNT(state, data) {
        state.posts_count = data;
    },
    SET_ALL_POST(state, data) {
        state.posts = data;
    },
    Delete_Post(state, data) {
        state.posts.splice(data, 1);
        state.posts_count--;
    }
};

export const actions = {
    async savePost(context, form) {
        let res = await axios.post("/api/posts", form);
        context.commit("SET_POST", res.data.data);
        return res;
    },
    async fetchAllPost(context) {
        if (!context.state.posts) {
            let res = await axios.get("/api/posts/all-posts");
            context.commit("SET_ALL_POST", res.data.data);
        }
    },
    async updatePost(context, { title, content, slug }) {
        await axios.get("/sanctum/csrf-cookie");
        const data = {
            title: title,
            content: content
        };
        context.commit("SET_POST", data);
        return axios.patch(`/api/posts/${slug}/edit`, data);
    },
    async editPost(context, form) {
        let res = await axios.patch(`/api/posts/${form.slug}`, form);
        context.commit("SET_POST", res.data.data);
        return res;
    },
    async deletePost(context, form) {
        await axios.delete(`/api/posts/${form.slug}`);
        context.commit("Delete_Post", form.index);
    }
};
