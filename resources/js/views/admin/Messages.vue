<template>
  <div class="admin-messages">
    <b-table :items="messages"></b-table>
  </div>
</template>
<script>
export default {
  data() {
    return {
      messages: []
    }
  },

  mounted() {
    this.fetchMessages()
  },

  methods: {
    fetchMessages() {
      this.$api.messages.fetch().then(response => {
        this.messages = response.data.data;
      })
    }
  }
}
</script>

