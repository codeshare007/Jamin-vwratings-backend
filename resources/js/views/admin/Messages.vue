<template>
  <div class="admin-messages">
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
        <b-button variant="primary" @click="fetchMessages">
          <b-icon-arrow-clockwise/>
        </b-button>
      </b-col>
    </div>
    <b-table
      table-variant="dark"
      ref="messagesTable"
      :sort-by.sync="sortBy"
      :sort-desc.sync="isDesc"
      :no-footer-sorting="false"
      :busy="loading"
      :items="messages"
      :fields="messagesFields"
    >
      <template #cell(select)="data">
        <div class="d-flex justify-content-center align-items-center h-100">
          <input type="checkbox" :data-id="data.item.id"/>
        </div>
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
      messages: [],
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
      messagesFields: [
        {key: 'select', label: '', thStyle: 'width: 100px;'},
        {key: 'index', label: '#'},
        {key: 'info', thStyle: 'max-width: 50px'},
        {key: 'content', thStyle: 'width: 300px'},
        {
          key: 'created_at', formatter: createdAt => {
            return moment(createdAt).format('YYYY-MM-DD HH:mm')
          }
        },
        {key: 'actions'}
      ]
    }
  },

  mounted() {
    this.fetchMessages()
  },

  methods: {
    fetchMessages() {
      this.$api.adminMessages.fetch(this.currentPage, this.params).then(response => {
        this.messages = response.data.data;
        this.total = response.data.total;
      })
    },

    handlePageChange(value) {
      this.params.page = value;
      this.fetchMessages()
    }
  }
}
</script>
<style lang="scss">
  .admin-messages {
    background: #24252d;
    padding: 25px;
    border-radius: 5px;
    margin-bottom: 100px;
  }
</style>

