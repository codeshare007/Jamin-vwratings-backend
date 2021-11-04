<template>
  <div class="admin-users">
    <div class="mb-3">
      <b-button variant="success">Create</b-button>
      <b-button variant="primary">
        <b-icon-arrow-clockwise/>
      </b-button>
    </div>

    <b-pagination/>

    <b-table :items="users" :fields="usersFields"></b-table>

    <b-pagination />

  </div>
</template>
<script>
export default {
  data() {
    return {
      users: [],
      usersFields: [
        {key: 'username'},
        {key: 'email'},
        {key: 'role'},
        {key: 'status'},
      ]
    }
  },

  mounted() {
    this.fetchUsers()
  },

  methods: {
    fetchUsers() {
      this.$api.users.fetch().then(response => {
        this.users = response.data.data;
      })
    }
  }
}
</script>
