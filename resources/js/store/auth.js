import Cookie from 'js-cookie';

export default {
  namespaced: true,
  state: {
    profile: null,
    loggedIn: !!Cookie.get('access_token'),
    hasRefreshToken: !!Cookie.get('refresh_token'),
    isAdmin: false,
  },

  getters: {
    profile: state => state.profile,
    loggedIn: state => state.loggedIn,
    isAdmin: state => state.isAdmin
  },

  mutations: {
    SET_PROFILE: (state, payload = null) => {
      state.profile = payload;
      state.loggedIn = !!payload;
    },
    SET_PROFILE_PERMISSIONS: (state, payload = null) => {
      state.permissions = payload;
    },
    SET_REFRESHED: (state, payload = false) => {
      state.isRefreshed = payload;
    },
    SET_ADMIN: (state, payload = false) => {
      state.isAdmin = payload
    }
  },

  actions: {
    LOGIN: async function ({dispatch}, payload) {
      let {data} = await this.$api.auth.login(payload);
      resetTokensByResponseData(data);
      await dispatch('GET_PROFILE');
    },

    RESET_PROFILE: async function ({commit}) {
      commit('SET_PROFILE');
      commit('SET_PROFILE_PERMISSIONS');
      Cookie.remove('access_token');
      Cookie.remove('refresh_token');
    },

    // eslint-disable-next-line no-unused-vars
    REFRESH_TOKEN: async function ({dispatch}, payload) {
      let {data} = await this.$api.auth.refreshToken(payload);
      resetTokensByResponseData(data);
      await dispatch('GET_PROFILE');
    },

    // eslint-disable-next-line no-unused-vars
    LOGOUT: async function ({commit}) {
      await this.$api.auth.logout();
      commit('SET_PROFILE');
      Cookie.remove('access_token');
      Cookie.remove('refresh_token');
    },

    GET_PROFILE: async function ({commit}) {
      let {data} = await this.$api.auth.me();
      commit('SET_ADMIN', data.role === 1);
      commit('SET_PROFILE', data);
    },
  },
};

// Private methods
function resetTokensByResponseData(responseData) {
  //let tokenExpires = responseData.data['access_token_expire_at'];
  // let refreshExpires = responseData.data['refresh_token_expire_at'];
  Cookie.set('access_token', responseData['access_token']);
  Cookie.set('refresh_token', responseData['access_token']);
}
