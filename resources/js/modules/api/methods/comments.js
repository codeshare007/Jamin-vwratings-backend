export default axios => ({
  fetch() {
    return axios.get('avis');
  },
  send(id, payload) {
    return axios.post(`avis/${id}/send`, payload)
  }
});
