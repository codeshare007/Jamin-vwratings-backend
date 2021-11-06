<template>
  <div class="admin-users">
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
        <b-button variant="success" class="mr-2">Create</b-button>
        <b-button variant="primary" @click="fetchUsers()">
          <b-icon-arrow-clockwise/>
        </b-button>
      </b-col>
    </div>
    <b-table
      table-variant="dark"
      ref="userTable"
      :sort-by.sync="sortBy"
      :sort-desc.sync="isDesc"
      :no-footer-sorting="false"
      :busy="loading"
      :items="users"
      :fields="usersFields"
      >
      <template #cell(select)="data">
        <b-checkbox />
      </template>
      <template #cell(index)="data">
        {{ data.index + 1 }}
      </template>
      <template #cell(role)="data">
        {{ data.item.role | role }}
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
      users: [],
      loading: false,
      sortBy: 'created_at',
      currentPage: 1,
      total: 1,
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
        {key: 'created_at', sortable: true, formatter: createdAt => {
            return moment(createdAt).format('YYYY-MM-DD HH:mm')
          }},
        {key: 'actions'}
      ]
    }
  },

  filters: {
    role(data) {
      const roles = {1: 'Admin', 2: 'User'}
      return roles[data];
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

    remove(id) {
      this.$api.adminUsers.delete(id).then(response => {
        this.fetchUsers()
      })
    },

    handlePageChange(value) {
      this.params.page = value;
      this.fetchUsers()
    }
  }
}
</script>
<style lang="scss">
  .admin-users {
    background: #24252d;
    padding: 25px;
    border-radius: 5px;
    margin-bottom: 100px;
  }
</style>
