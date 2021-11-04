<template>
  <div class="admin-avis">
    <div class="mb-3">
      <b-button variant="success">Create</b-button>
    </div>
    <b-pagination
      v-model="currentPage"
      @change="handlePageChange"
      :total-rows="total"
    />
    <b-table :items="avis" :fields="avisFields">
      <template #cell(index)="data">
        {{ data.index + 1 }}
      </template>
      <template #cell(actions)="row">
        <b-button variant="success" size="sm">
          <b-icon-wrench/>
        </b-button>
        <b-button variant="danger" size="sm" @click="remove(row.item.id)">
          <b-icon-trash/>
        </b-button>
      </template>
    </b-table>
    <b-pagination
      v-model="currentPage"
      @change="handlePageChange"
      :total-rows="total"
    />
  </div>
</template>
<script>
export default {
  data() {
    return {
      avis: [],
      avisFields: [
        {key: 'select', label: ''},
        {key: 'index', label: '#'},
        {key: 'id'},
        {key: 'user.username', label: 'user'},
        {key: 'name'},
        {key: 'status'},
        {key: 'average_rating', formatter: (data) => {return data.toFixed(2)}},
        {key: 'actions'}
      ]
    }
  },

  mounted() {
    this.fetchAvis(1)
  },

  methods: {
    fetchAvis(page) {
      this.$api.adminAvis.fetch(page).then(response => {
        this.avis = response.data.data;
        this.total = response.data.total;
      })
    },
    handlePageChange(value) {
      this.fetchAvis(value)
    }
  }
}
</script>
