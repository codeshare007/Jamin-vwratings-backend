export default axios => ({
  fetch() {
    return axios.get('profile');
  },
  update(payload) {
    return axios.post('profile', payload)
  },
  changeAvatar(payload) {
    return axios.post('profile/change-avatar', payload, {
      headers: {'content-type': 'multipart/form-data'}
    });
  },
});
