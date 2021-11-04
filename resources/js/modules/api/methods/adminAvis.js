export default axios => ({
  fetch() {
    return axios.get('admin/avis');
  },
});
