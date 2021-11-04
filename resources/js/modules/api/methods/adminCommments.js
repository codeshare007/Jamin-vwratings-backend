export default axios => ({
  fetch(page = 1) {
    return axios.get('admin/comments', {params: {page: page}});
  },
});
