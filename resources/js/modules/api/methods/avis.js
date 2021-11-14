export default axios => ({
  fetch(page = 1, filter = {}) {
    return axios.get('avis', {
      params: {page: page, ...filter}
    });
  },
  get(id) {
    return axios.get(`avis/${id}`)
  },
  create(payload) {
    return axios.post('avis', payload)
  },
  rating(id, payload) {
    return axios.post(`avis/${id}/rate`, payload)
  },
  comment(id, payload) {
    return axios.post(`avis/${id}/comment`, payload, {
      headers: {'Content-Type': 'multipart/form-data'}
    })
  },
});
