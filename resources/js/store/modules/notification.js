export const namespaced = true;

export const state = {
    NotifyItems: [],
    notifyCount: 0
};

export const mutations = {
    Set_Notifications(state, payload) {
        state.NotifyItems = payload.data;
        state.notifyCount = payload.notifyCount;
    }
};

export const actions = {
    async getNotifications(context) {
        if (context.state.NotifyItems.length == 0) {
            axios.get(`/api/notification`).then(res => {
                context.commit("Set_Notifications", res.data);
                // this.NotifyItems = res.data.data;
                // this.notifyCount = res.data.notifyCount;
            });
            return;
        }
    }
};
