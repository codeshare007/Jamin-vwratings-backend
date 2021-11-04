export default axios => ({
  fetch(page = 1) {
    return axios.get('admin/users', {params: {page: page}});
  },
});
