<template>
  <div class="admin-messages">
    <div class="d-flex justify-content-between mb-3">
      <b-col class="p-0 d-flex justify-items-start">
        <b-pagination
          class="m-0"
          v-model="currentPage"
          @change="handlePageChange"
          :total-rows="total"
        />
        <div class="ml-3" v-if="ids.length > 0">
          <b-button
            variant="danger"
            @click="$refs['bulkModal'].show()"
          >Bulk Delete
          </b-button>
        </div>
      </b-col>
      <b-col class="p-0 d-flex justify-content-end align-items-center">
        <b-form-input
          class="mr-2 search-link"
          v-model="search"
          placeholder="Search..."
        />
        <b-button variant="primary" @click="fetchMessages()">
          <b-icon-arrow-clockwise/>
        </b-button>
      </b-col>
    </div>
    <b-table
      no-local-sorting
      table-variant="dark"
      ref="messagesTable"
      :sort-by.sync="sortBy"
      :sort-desc.sync="isDesc"
      :no-footer-sorting="false"
      :busy="loading"
      select-mode="multi"
      :items="messages"
      :fields="messagesFields"
    >
      <template #head(select)="data">
        <div class="d-flex justify-content-center align-items-center h-100">
          <b-checkbox type="checkbox" @change="selectAllRows"/>
        </div>
      </template>

      <template #cell(select)="data">
        <div class="d-flex justify-content-center align-items-center h-100">
          <b-checkbox
            type="checkbox"
            :checked="data.item.selected"
            v-model="data.item.selected"
            @change="rowSelected"
          />
        </div>
      </template>

      <template #cell(id)="data">
        {{ data.item.id }}
      </template>

      <template #cell(name)="data">
        <div class="d-flex">
          <div v-if="data.item.name" class="mr-3">
            <span class="d-block">Name: </span>
            <span class="d-block font-weight-bold">{{ data.item.name }}</span>
          </div>
          <div v-if="data.item.email">
            <span class="d-block">Email: </span>
            <span class="d-block font-weight-bold">{{ data.item.email }}</span>
          </div>
        </div>
        <p class="mt-3">{{ data.item.content }}</p>
      </template>

      <template #cell(action)="data">
        <b-button
          variant="danger"
          size="sm"
          @click="showDeleteModal(data.item.id)">
          <b-icon-trash/>
        </b-button>
      </template>

    </b-table>
    <b-pagination
      v-model="currentPage"
      @change="handlePageChange"
      :total-rows="total"
    />

    <b-modal ref="bulkModal" title="Delete Messages" @ok="bulkDelete" ok-variant="danger" ok-title="Delete">
      Are you sure that you want to delete selected messages?
    </b-modal>

    <b-modal ref="deleteModal" title="Delete Message" @ok="remove" ok-variant="danger" ok-title="Delete">
      Are you sure that you want to delete this message?
    </b-modal>
  </div>
</template>
<script>
import moment from "moment";

export default {
  data() {
    return {
      ids: [],
      messages: [],
      loading: false,
      selectAll: false,
      sortBy: 'created_at',
      currentPage: 1,
      total: 1,
      isDesc: false,
      search: null,
      deletableId: null,
      params: {
        search: '',
        sortBy: 'created_at',
        sort: 'desc',
        page: 1
      },
      messagesFields: [
        {key: 'select', label: '', thStyle: 'width: 70px;', sortable: false},
        {key: 'id', label: '#', sortable: true},
        {key: 'name', label: 'content', sortable: true},
        {
          key: 'created_at',
          label: 'created at',
          thStyle: 'white-space: nowrap; width: 180px',
          sortable: true,
          formatter: createdAt => {
            return moment(createdAt).format('YYYY-MM-DD HH:mm')
          }
        },
        {key: 'action', label: '', thStyle: 'width: 50px', sortable: false}
      ]
    }
  },

  watch: {
    sortBy(data) {
      if (data) {
        this.params.sortBy = data;
        this.fetchMessages()
      }
    },
    isDesc(data) {
      if (data) {
        this.params.sort = (data === true ? 'desc' : 'asc')
        this.fetchMessages()
      }
    },
    search(data) {
      this.params.search = data;
      this.fetchMessages()
    }
  },

  mounted() {
    this.fetchMessages()
  },

  methods: {
    onRowSelected(items) {
      this.selected = items
    },

    rowSelected() {
      this.ids = this.messages
        .filter(item => {
          if (item.selected) return item.id
        })
        .map(item => item.id);
    },

    showDeleteModal(id) {
      this.deletableId = id;
      this.$refs['deleteModal'].show()
    },

    fetchMessages() {
      this.loading = true;
      this.$api.adminMessages
        .fetch(this.currentPage, this.params)
        .then(response => {
        this.messages = response.data.data.map(item => {
          item.selected = false;
          return item;
        });
        this.total = response.data.total;
        this.loading = false;
      })
    },

    selectAllRows() {
      this.selectAll = !this.selectAll;
      this.messages = this.messages.map(item => {
        item.selected = this.selectAll;
        return item;
      })

      this.ids = this.messages
        .filter(item => {
          if (item.selected) return item.id
        })
        .map(item => item.id);
    },

    remove() {
      this.$api.adminMessages.delete(this.deletableId).then(() => {
        this.fetchMessages();
        this.deletableId = null;
      })
    },

    bulkDelete() {
      this.$api.adminMessages.bulkDelete(this.ids).then(() => {
        this.ids = [];
        this.fetchMessages();
      });
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

