export default {
    namespaced: true,
    state: {
        profile: null
    },

    getters: {
        profile: state => state.profile,
    },

    mutations: {
        SET_PROFILE: (state, payload = null) => {
            state.profile = payload;
        },
    },

    actions: {
        FETCH_PROFILE: async function({commit}) {
            let { data } = await this.$api.profile.fetch();
            await commit('SET_PROFILE', data);
        },
    }
}