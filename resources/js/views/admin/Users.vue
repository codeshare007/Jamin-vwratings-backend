<template>
  <div class="admin-users">
    <div class="mb-3">
      <b-button variant="success">Create</b-button>
      <b-button variant="primary">
        <b-icon-arrow-clockwise/>
      </b-button>
    </div>
    <b-pagination
      v-model="currentPage"
      @change="handlePageChange"
      :total-rows="total"/>
    <b-table :items="users" :fields="usersFields">
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
export default {
  data() {
    return {
      currentPage: 1,
      total: 1,
      users: [],
      usersFields: [
        {key: 'select', label: ''},
        {key: 'index', label: '#'},
        {key: 'username'},
        {key: 'email'},
        {key: 'role'},
        {key: 'status'},
        {key: 'actions'}
      ]
    }
  },

  mounted() {
    this.fetchUsers(1)
  },

  methods: {
    fetchUsers(page) {
      this.$api.adminUsers.fetch(page).then(response => {
        this.users = response.data.data;
        this.total = response.data.total
      })
    },

    handlePageChange(value) {
      this.fetchUsers(value)
    }
  }
}
</script>
