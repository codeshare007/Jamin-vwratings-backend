export default axios => ({
  fetch(page = 1) {
    return axios.get('admin/avis', {params: {page: page}});
  },
});
