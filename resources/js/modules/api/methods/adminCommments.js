export default axios => ({
  fetch(page = 1, filter = {}) {
    return axios.get('admin/comments', {
      params: {page: page, ...filter}
    });
  },
  get(id) {
    return axios.get(`admin/comments/${id}`)
  },
  create(payload) {
    return axios.post('admin/comments', payload)
  },
  update(id, payload) {
    return axios.put(`admin/comments/${id}`, payload)
  },
  delete(id) {
    return axios.delete(`admin/comments/${id}`)
  },
  bulkDelete(ids) {
    return axios.post('admin/comments/bulk-delete', {ids: ids})
  },
  bulkOpinion(ids, opinion) {
    return axios.post('admin/comments/bulk-opinion', {ids: ids, opinion: opinion})
  }
});
