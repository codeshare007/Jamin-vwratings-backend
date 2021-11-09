export default axios => ({
  fetch(page = 1, filter = {}) {
    return axios.get('avis', {
      params: {
        page: page,
        ...filter
      }
    });
  },
  /*
  fetch(payload = {}) {
    let params = {page: 1, per_page: 10}
    if (payload.query) params.query = payload.query;
    if (payload.page) params.page = payload.page;
    if (payload.per_page) params.per_page = payload.per_page;
    if (payload.type) params.type = payload.type;

    return axios.get('avis', {params: params}).then(response => {
      return response.data;
    });
  },

   */
  rating(id, payload) {
    return axios.post(`avis/${id}/rate`, payload)
  },
  comment(id, payload) {
    return axios.post(`avis/${id}/comment`, payload, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
  },
  get(id) {
    return axios.get(`avis/${id}`)
  },
  create(payload) {
    return axios.post('avis/create', payload)
  }
});
