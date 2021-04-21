import axios from "axios";

export const namespaced = true;

export const state = {
    draft: null,
    drafts: null,
    drafts_count: null
};

export const mutations = {
    Set_Draft(state, data) {
        state.draft = data;
    },
    Set_All_Drafts(state, data) {
        state.drafts = data;
    },
    Set_Drafts_Count(state, data) {
        state.drafts_count = data;
    },
    Delete_Drafts(state, data) {
        state.drafts.splice(data, 1);
        state.drafts_count--;
    }
};

export const actions = {
    async saveDraft(context, { requestType, requestUrl, title, content }) {
        await axios.get("/sanctum/csrf-cookie");
        const data = {
            title: title,
            content: content
        };
        context.commit("Set_Draft", data);
        return axios[requestType](requestUrl, data);
    },
    async getDraft(context, link) {
        if (context.state.draft == null) {
            let response = await axios.get(`/api/drafts/${link}`);
            const data = {
                title: response.data.title,
                content: response.data.content
            };
            context.commit("Set_Draft", data);
        }

        return context.state.draft;
    },
    async fetchAllDraft(context, data) {
        if (!context.state.drafts) {
            let res = await axios.get("/api/posts/all-drafts");
            context.commit("Set_All_Drafts", res.data.data);
            context.commit("Set_Drafts_Count", res.data.draft_count);
            context.commit("post/SET_POST_COUNT", res.data.draft_count, {
                root: true
            });
        }
    },
    async deleteDraft(context, form) {
        await axios.delete(`/api/drafts/${form.link}`);
        context.commit("Delete_Drafts", form.index);
    }
};
