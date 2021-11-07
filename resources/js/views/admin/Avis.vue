<template>
  <div class="admin-avis">
    <div class="d-flex justify-content-between mb-3">
      <b-col class="p-0">
        <b-pagination
          class="m-0"
          v-model="currentPage"
          @change="handlePageChange"
          :total-rows="total"
        />
      </b-col>
      <b-col class="p-0 d-flex justify-content-end align-items-center">
        <b-form-input class="mr-2 search-link" v-model="params.search" placeholder="Search..." />
        <b-button variant="primary" @click="fetchAvis()">
          <b-icon-arrow-clockwise/>
        </b-button>
      </b-col>
    </div>
    <b-table
      table-variant="dark"
      ref="avisTable"
      :sort-by.sync="sortBy"
      :sort-desc.sync="isDesc"
      :no-footer-sorting="false"
      :fields="avisFields"
      :busy="loading"
      :items="avis"
    >
      <template #cell(index)="data">
        {{ data.index + 1 }}
      </template>
      <template #cell(actions)="row">
        <b-button variant="primary" size="sm">
          <b-icon-pencil />
        </b-button>
        <b-button variant="danger" size="sm" @click="remove(row.item.id)">
          <b-icon-trash />
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
      loading: false,
      sortBy: 'created_at',
      isDesc: false,
      currentPage: 1,
      total: 1,
      params: {
        search: '',
        sortBy: 'created_at',
        sort: 'desc',
        page: 1
      },
      avisFields: [
        {key: 'select', label: ''},
        {key: 'id', label: '#', sortable: true},
        {key: 'user.username', formatter: item => {
            return item ? item: '—'
          }, label: 'user', sortable: true},
        {key: 'name', sortable: true},
        {key: 'average_rating', formatter: (data) => {return data.toFixed(2)}, sortable: true},
        {key: 'actions'}
      ]
    }
  },

  mounted() {
    this.fetchAvis()
  },

  methods: {
    fetchAvis() {
      this.$api.adminAvis.fetch(this.currentPage, this.params).then(response => {
        this.avis = response.data.data;
        this.total = response.data.total;
      })
    },
    handlePageChange(value) {
      this.params.page = value;
      this.fetchAvis()
    }
  }
}
</script>
<style lang="scss">
  .admin-avis {
    background: #24252d;
    padding: 25px;
    border-radius: 5px;
    margin-bottom: 100px;
  }
</style>
