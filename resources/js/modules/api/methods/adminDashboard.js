export default axios => ({
  information() {
    return axios.get('admin/dashboard/information')
  },
});
