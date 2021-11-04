<template>
  <div>
    <nav class="mb-3">
      <b-nav vertical>
        <b-nav-item v-for="(item, key) in menu" :key="key" :to="{ name: item.name}">{{ item.title }}</b-nav-item>
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
          {title: 'Avis', name: 'admin.avis'},
          {title: 'Comments', name: 'admin.comments'},
          {title: 'Ratings', name: 'admin.ratings'},
          {title: 'Messages', name: 'admin.messages'},
        ]
      }
    }
  }
</script>
