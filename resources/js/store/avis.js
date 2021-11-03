export default {
  namespaced: true,
  state: {
    avis: []
  },

  getters: {
    avis: state => state.avis,
  },

  mutations: {
    SET_AVIS: (state, payload = null) => {
      state.avis = payload;
    },
  },

  actions: {
    FETCH_AVIS: async function({commit}) {
      let { data } = await this.$api.avis.fetch();
      await commit('SET_AVIS', data);
    },
  }
}
