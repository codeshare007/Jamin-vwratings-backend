export default axios => ({
  fetch(page = 1, filter = {}) {
    return axios.get('admin/users', {
      params: {page: page, ...filter}
    });
  },
  get(id) {
    return axios.get(`admin/users/${id}`)
  },
  create(payload) {
    return axios.post('admin/users', payload)
  },
  update(id, payload) {
    return axios.put(`admin/users/${id}`, payload)
  },
  delete(id) {
    return axios.delete(`admin/users/${id}`)
  },
  bulkDelete(ids) {
    return axios.post('admin/users/bulk-delete', {ids: ids})
  }
});
