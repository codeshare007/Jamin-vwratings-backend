export default axios => ({
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
  get(id) {
    return axios.get(`avis/${id}`)
  }
});
