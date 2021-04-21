export const namespaced = true;

export const state = {
    categories: []
};

export const mutations = {
    Set_Categories(state, categories) {
        state.categories = categories;
    }
};

export const actions = {
    async getNavbarCategories(context) {
        if (context.state.categories.length == 0) {
            await axios.get(`/api/navbar-category`).then(res => {
                context.commit("Set_Categories", res.data.data);
            });
            return;
        }
    }
};
