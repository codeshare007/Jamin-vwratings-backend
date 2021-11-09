export default axios => ({
  fetch(page = 1, filter = {}) {
    return axios.get('admin/messages', {
      params: {
        page: page,
        ...filter
      }
    });
  },
});
