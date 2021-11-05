<template>
  <div class="admin-users">

    <div class="d-flex justify-content-between">
      <b-col>
        <b-pagination
          v-model="currentPage"
          @change="handlePageChange"
          :total-rows="total"/>
      </b-col>
      <b-col class="d-flex justify-content-end align-items-center">
        <b-form-input class="mr-2" v-model="params.search" placeholder="Search..." />
        <b-button variant="success" class="mr-2">Create</b-button>
        <b-button variant="primary" @click="fetchUsers()">
          <b-icon-arrow-clockwise/>
        </b-button>
      </b-col>
    </div>
    <b-table
      ref="userTable"
      :sort-by.sync="sortBy"
      @sort-changed="sortChanged"
      :sort-desc.sync="isDesc"
      :no-footer-sorting="false"
      :items="users"
      :fields="usersFields"
      :busy="loading">
      <template #cell(select)="data">
        <b-checkbox />
      </template>
      <template #cell(index)="data">
        {{ data.index + 1 }}
      </template>
      <template #cell(actions)="row">
        <b-button variant="primary" size="sm">
          <b-icon-pencil />
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
import moment from "moment";

export default {
  data() {
    return {
      currentPage: 1,
      total: 1,
      users: [],
      loading: false,
      sortBy: 'created_at',
      isDesc: false,
      params: {
        search: '',
        sortBy: 'created_at',
        sort: 'desc',
        page: 1
      },
      usersFields: [
        {key: 'select', label: ''},
        {key: 'index', label: '#', sortable: true},
        {key: 'username', sortable: true},
        {key: 'email', sortable: true},
        {key: 'role', sortable: true},
        {key: 'status', sortable: true},
        {key: 'created_at', sortable: true, formatter: createdAt => {
            return moment(createdAt).format('YYYY-MM-DD HH:mm:ss')
          }},
        {key: 'actions'}
      ]
    }
  },


  watch: {
    sortBy(data) {
      this.params.sortBy = data;
      this.fetchUsers();
    },
    isDesc(data) {
      this.params.sort = (data === true ? 'desc' : 'asc')
      this.fetchUsers();
    },
    'params.search'(data) {
      this.fetchUsers()
    }
  },

  mounted() {
    this.fetchUsers()
  },

  methods: {
    fetchUsers() {
      this.loading = true;
      this.$api.adminUsers.fetch(this.currentPage, this.params).then(response => {
        this.users = response.data.data;
        this.currentPage = response.data.current_page;
        this.total = response.data.total;
        this.loading = false;
      })
    },

    sortChanged() {

    },

    handlePageChange(value) {
      this.params.page = value;
      this.fetchUsers()
    }
  }
}
</script>
