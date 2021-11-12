export default axios => ({
  fetch(page = 1, filter = {}) {
    return axios.get('parties', {
      params: {page: page, ...filter}
    });
  },
  get(id) {
    return axios.get(`parties/${id}`)
  },
  create(payload) {
    return axios.post('parties/create', payload)
  },
  rating(id, payload) {
    return axios.post(`parties/${id}/rate`, payload)
  },
  comment(id, payload) {
    return axios.post(`parties/${id}/comment`, payload, {
      headers: {'Content-Type': 'multipart/form-data'}
    })
  },
});
