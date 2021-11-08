let refreshTokenPromise = null;

export default axios => ({
  login(payload) {
    return axios.post('auth/login', payload);
  },
  logout() {
    return axios.post('auth/logout');
  },
  clear() {
    return axios.post('auth/clear');
  },
  refreshToken(payload) {
    if (refreshTokenPromise) {
      return refreshTokenPromise;
    }

    refreshTokenPromise = axios.post(`auth/refresh`, payload);
    refreshTokenPromise.finally(() => {
      refreshTokenPromise = null;
    });

    return refreshTokenPromise;
  },
  me() {
    return axios.get('auth/me');
  },
  profile(payload) {
    return axios.get(`profile`, payload);
  },
  register(payload) {
    return axios.post('auth/register', payload).then(response => {
      return response.data;
    })
  }
})
