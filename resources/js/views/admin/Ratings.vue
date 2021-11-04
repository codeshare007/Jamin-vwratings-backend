<template>
  <div class="admin-ratings">
    <b-table :items="ratings"></b-table>
  </div>
</template>
<script>
export default {
  data() {
    return {
      ratings: []
    }
  },

  mounted() {
    this.fetchRatings()
  },

  methods: {
    fetchRatings() {
      this.$api.ratings.fetch().then(response => {
        this.ratings = response.data.data;
      })
    }
  }
}
</script>

