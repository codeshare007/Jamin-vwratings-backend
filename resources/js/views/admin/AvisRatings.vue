<template>
  <div class="admin-ratings">
    <b-pagination v-model="currentPage" @change="handlePageChange" :total-rows="total" />
    <b-table :items="ratings" :fields="ratingFields">
      <template #cell(index)="data">
        {{ data.index + 1 }}
      </template>
      <template #cell(actions)="row">
        <b-button variant="primary" size="sm" class="mr-2"><b-icon-pencil /></b-button>
        <b-button variant="danger" size="sm" @click="remove(row.item.id)"><b-icon-trash /></b-button>
      </template>
    </b-table>
    <b-pagination v-model="currentPage" @change="handlePageChange" :total-rows="total" />
  </div>
</template>
<script>
import moment from "moment";

export default {
  data() {
    return {
      ratings: [],
      currentPage: 1,
      total: 1,
      ratingFields: [
        {key: 'index', label: '#'},
        {key: 'user.username', label: 'user'},
        {key: 'avi.name', label: 'avi'},
        {key: 'rating'},
        {key: 'created_at', formatter: createdAt => {
            return moment(createdAt).format('YYYY-MM-DD HH:mm:ss')
          }},
        {key: 'updated_at', formatter: createdAt => {
            return moment(createdAt).format('YYYY-MM-DD HH:mm:ss')
          }},
        {key: 'actions'}
      ]
    }
  },

  mounted() {
    this.fetchRatings(1)
  },

  methods: {
    fetchRatings(page) {
      this.$api.adminRatings.fetch(page).then(response => {
        this.ratings = response.data.data;
        this.total = response.data.total;
      })
    },

    handlePageChange(value) {
      this.fetchRatings(value)
    }
  }
}
</script>

