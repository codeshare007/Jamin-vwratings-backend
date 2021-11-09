export default axios => ({
  fetch(page = 1, filter = {}) {
    return axios.get('admin/messages', {
      params: {
        page: page,
        ...filter
      }
    });
  },
  delete(id) {
    return axios.delete(`admin/messages/${id}`)
  },
  bulkDelete(ids) {
    return axios.post('admin/messages/bulk-delete', {ids: ids})
  },
});
