export default axios => ({
  fetch(page = 1, filter = {}) {
    return axios.get('admin/campaigns', {
      params: {page: page, ...filter}
    });
  },
  get(id) {
    return axios.get(`admin/campaigns/${id}`)
  },
  create(payload) {
    return axios.post('admin/campaigns', payload)
  },
  update(id, payload) {
    return axios.put(`admin/campaigns/${id}`, payload)
  },
  delete(id) {
    return axios.delete(`admin/campaigns/${id}`)
  },
  bulkDelete(ids) {
    return axios.post('admin/campaigns/bulk-delete', {ids: ids})
  }
});
