<template>
  <div class="admin-comments">
    <b-table :items="comments"></b-table>
  </div>
</template>
<script>
export default {
  data() {
    return {
      comments: []
    }
  },

  mounted() {
    this.fetchComments()
  },

  methods: {
    fetchComments() {
      this.$api.comments.fetch().then(response => {
        this.comments = response.data.data;
      })
    }
  }
}
</script>
