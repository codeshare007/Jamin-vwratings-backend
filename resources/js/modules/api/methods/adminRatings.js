export default axios => ({
  fetch(page = 1) {
    return axios.get('admin/ratings', {params: {page: page}});
  },
});
