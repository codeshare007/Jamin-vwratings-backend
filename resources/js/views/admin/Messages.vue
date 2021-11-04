<template>
  <div class="admin-messages">
    <b-pagination v-model="currentPage" @change="handlePageChange" :total-rows="total" />
    <b-table :items="messages" :fields="messagesFields">
      <template #cell(select)="data">
        <input type="checkbox" :data-id="data.item.id"/>
      </template>
      <template #cell(index)="data">
        {{ data.index + 1 }}
      </template>
      <template #cell(info)="data">
        <b>{{ data.item.name }}</b>
        {{ data.item.email }}
      </template>
      <template #cell(actions)="row">
        <b-button variant="danger" size="sm" @click="remove(row.item.id)">
          <b-icon-trash />
        </b-button>
      </template>
    </b-table>
    <b-pagination v-model="currentPage" @change="handlePageChange" :total-rows="total"/>
  </div>
</template>
<script>
import moment from "moment";

export default {
  data() {
    return {
      currentPage: 1,
      total: 1,
      messages: [],
      messagesFields: [
        {key: 'select', label: ''},
        {key: 'index', label: '#'},
        {key: 'info', thStyle: 'width: 100px'},
        {key: 'content', thStyle: 'width: 300px'},
        {
          key: 'created_at', formatter: createdAt => {
            return moment(createdAt).format('YYYY-MM-DD HH:mm:ss')
          }
        },
        {key: 'actions'}
      ]
    }
  },

  mounted() {
    this.fetchMessages(1)
  },

  methods: {
    fetchMessages(page) {
      this.$api.adminMessages.fetch(page).then(response => {
        this.messages = response.data.data;
        this.total = response.data.total;
      })
    },

    handlePageChange(value) {
      this.fetchMessages(value)
    }
  }
}
</script>

