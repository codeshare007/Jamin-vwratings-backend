export default axios => ({
  fetch(page = 1, filter = {}) {
    return axios.get('admin/users', {
      params: {
        page: page,
        ...filter
      }
    });
  },
  show(id) {
    return axios.get(`admin/users/${id}`)
  },
  create(payload) {
    return axios.post('admin/users/create', payload)
  },
  edit(id, payload) {
    return axios.post(`admin/users/${id}`, payload)
  },
  delete(id) {
    return axios.delete(`admin/users/${id}`)
  }
});
