export default axios => ({
  fetch(page = 1, filter = {}) {
    return axios.get('admin/avis', {
      params: {page: page, ...filter}
    });
  },
  get(id) {
    return axios.get(`admin/avis/${id}`)
  },
  create(payload) {
    return axios.post('admin/avis', payload)
  },
  update(id, payload) {
    return axios.put(`admin/avis/${id}`, payload)
  },
  delete(id) {
    return axios.delete(`admin/avis/${id}`)
  },
  bulkDelete(ids) {
    return axios.post('admin/avis/bulk-delete', {ids: ids})
  }
});
