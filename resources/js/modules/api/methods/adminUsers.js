export default axios => ({
  fetch(page = 1, filter = {}) {
    return axios.get('admin/users', {
      params: {
        page: page,
        ...filter
      }
    });
  },
});
