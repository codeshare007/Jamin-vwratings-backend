<template>
  <div class="adminTable">
    <div class="d-flex justify-content-between mb-3">
      <b-col class="p-0  d-flex justify-items-start">
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
        <b-form-input class="mr-2 search-link" v-model="search" placeholder="Search..."/>
        <b-button variant="success" @click="create" class="mr-2">Create</b-button>
        <b-button variant="primary" @click="fetchItems">
          <b-icon-arrow-clockwise/>
        </b-button>
      </b-col>
    </div>
    <b-table
      table-variant="dark"
      ref="userTable"
      no-local-sorting
      :sort-by.sync="sortBy"
      :sort-desc.sync="isDesc"
      :no-footer-sorting="false"
      :busy="loading"
      :items="items"
      :fields="fields">

      <template #head(select)="row">
        <div class="d-flex justify-content-center align-items-center h-100">
          <b-checkbox type="checkbox" @change="selectAllRows"/>
        </div>
      </template>

      <template #cell(select)="row">
        <div class="d-flex justify-content-center align-items-center h-100">
          <b-checkbox
            type="checkbox"
            :checked="data.item.selected"
            @change="rowSelected"
            v-model="data.item.selected"
          />
        </div>
      </template>

      <template #cell(index)="data">
        {{ data.index + 1 }}
      </template>

      <template #cell(role)="data">
        {{ data.item.role | role }}
      </template>

      <template #cell(actions)="data">
        <div class="d-flex">
          <b-button class="mr-2" variant="primary" size="sm" @click="edit(data.item.id)">
            <b-icon-pencil/>
          </b-button>
          <b-button
            variant="danger"
            size="sm" @click="showDeleteModal(data.item.id)">
            <b-icon-trash/>
          </b-button>
        </div>
      </template>
    </b-table>
    <b-pagination
      v-model="currentPage"
      @change="handlePageChange"
      :total-rows="total"
    />

    <b-modal ref="bulkModal" title="Delete Users" @ok="bulkDelete" ok-variant="danger" ok-title="Delete">
      Are you sure that you want to delete selected users?
    </b-modal>

    <b-modal ref="deleteModal" title="Delete User" @ok="remove" ok-variant="danger" ok-title="Delete">
      Are you sure that you want to delete this user?
    </b-modal>
  </div>
</template>
<script>
import {mapActions} from "vuex";

export default {
  props: {
    fields: Array,
    entity: String,
    entities: String
  },

  data() {
    return {
      ids: [],
      users: [],
      loading: false,
      selectAll: false,
      sortBy: 'created_at',
      search: '',
      currentPage: 1,
      deletableId: null,
      total: 1,
      isDesc: false,
      params: {
        search: '',
        sortBy: 'created_at',
        sort: 'desc',
        page: 1
      },
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
      if (data) {
        this.params.sortBy = data;
        this.fetchItems();
      }
    },
    isDesc(data) {
      this.params.sort = (data === true ? 'desc' : 'asc')
      this.fetchItems();
    },
    search(data) {
      this.params.search = data;
      this.fetchItems()
    }
  },

  mounted() {
    this.fetchItems()
  },

  methods: {

    ...mapActions({
      createUser: 'dialogs/user/create',
      editUser: 'dialogs/user/edit',
      fetchUsers: 'users/FETCH'
    }),

    fetchUsers() {
      this.loading = true;
      this.$api.adminUsers.fetch(this.currentPage, this.params).then(response => {
        this.users = response.data.data.map(item => {
          item.selected = false;
          return item;
        });
        this.currentPage = response.data.current_page;
        this.total = response.data.total;
        this.loading = false;
      })
    },

    create() {
      this.createUser().then(() => this.fetchUsers());
    },
    edit(id) {
      this.editUser(id).then(() => this.fetchUsers());
    },
    remove() {
      this.$api.adminUsers.delete(this.deletableId).then(response => {
        if (response.data.status === 'success') {
          this.fetchUsers();
          this.deletableId = null;
        }
      })
    },

    bulkDelete() {
      this.$api.adminUsers.bulkDelete(this.ids).then(() => {
        this.ids = [];
        this.fetchUsers();
      });
    },

    selectAllRows() {
      this.selectAll = !this.selectAll;
      this.users = this.users.map(item => {
        item.selected = this.selectAll;
        return item;
      })

      this.ids = this.users
        .filter(item => {
          if (item.selected) return item.id
        })
        .map(item => item.id);
    },

    rowSelected() {
      this.ids = this.users
        .filter(item => {
          if (item.selected) return item.id
        })
        .map(item => item.id);
    },

    showDeleteModal(id) {
      this.deletableId = id;
      this.$refs['deleteModal'].show()
    },

    handlePageChange(value) {
      this.params.page = value;
      this.fetchUsers()
    }
  }
}
</script>
<style lang="scss">
  .adminTable {
    background: #24252d;
    padding: 25px;
    border-radius: 5px;
    margin-bottom: 100px;
  }
</style>
