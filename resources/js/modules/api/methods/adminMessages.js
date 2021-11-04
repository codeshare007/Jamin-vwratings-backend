export default axios => ({
  fetch(page = 1) {
    return axios.get('admin/messages', {params: {page: page}});
  },
});
