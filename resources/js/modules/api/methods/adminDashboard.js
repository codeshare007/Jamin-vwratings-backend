export default axios => ({
  information() {
    return axios.get('admin/dashboard')
  },
  hits() {
    return axios.get('admin/dashboard/hits')
  },
  changeHits(value) {
    return axios.post('admin/dashboard/hits', {value: value})
  }
});
