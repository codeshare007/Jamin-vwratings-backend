<template>
  <div class="admin-sidebar">
    <nav>
      <b-nav vertical>
        <b-nav-item
          v-for="(item, key) in menu"
          :key="key"
          :to="{ name: item.name}"
        >{{ item.title }}</b-nav-item>
      </b-nav>
    </nav>
  </div>
</template>
<script>
  export default {
    data() {
      return {
        menu: [
          {title: 'Dashboard', name: 'admin.dashboard'},
          {title: 'Users', name: 'admin.users'},
          {title: 'Messages', name: 'admin.messages'},
          {title: 'Avis', name: 'admin.avis'},
          {title: 'Avis Comments', name: 'admin.comments'},
          {title: 'Avis Ratings', name: 'admin.ratings'},
          {title: 'Parties', name: 'admin.parties'},
          {title: 'Parties Comments', name: 'admin.parties.comments'},
          {title: 'Parties Ratings', name: 'admin.parties.ratings'}
        ]
      }
    }
  }
</script>
<style lang="scss">
  .admin-sidebar {
    background: #24252d;
    padding: 10px 0;
    border-radius: 5px;
    position: sticky;
    top: 20px;
    .nav-link {
      color: white;
      font-size: 20px;
      font-family: 'Futura PT', sans-serif;
    }
  }
</style>
