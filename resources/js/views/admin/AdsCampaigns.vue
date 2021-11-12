<template>
  <div class="admin-avis">
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
        <b-button variant="primary" @click="fetchAdsCampaigns()">
          <b-icon-arrow-clockwise/>
        </b-button>
      </b-col>
    </div>
    <b-table
      table-variant="dark"
      ref="avisTable"
      no-local-sorting
      :sort-by.sync="sortBy"
      :sort-desc.sync="isDesc"
      :no-footer-sorting="false"
      :fields="avisFields"
      :busy="loading"
      :items="campaigns"
    >

      <template #cell(index)="data">
        {{ data.index + 1 }}
      </template>

      <template #cell(actions)="row">
        <b-button variant="primary" size="sm" @click="edit(row.item.id)">
          <b-icon-pencil />
        </b-button>
        <b-button variant="danger" size="sm" @click="showDeleteModal(row.item.id)">
          <b-icon-trash />
        </b-button>
      </template>

    </b-table>
    <b-pagination
      v-model="currentPage"
      @change="handlePageChange"
      :total-rows="total"
    />

    <b-modal ref="deleteModal" title="Delete Avi" @ok="remove" ok-variant="danger" ok-title="Delete">
      Are you sure that you want to delete this avi?
    </b-modal>

  </div>
</template>
<script>
import moment from "moment";
import {mapActions} from "vuex";

export default {
  data() {
    return {
      ids: [],
      campaigns: [],
      loading: false,
      selectAll: false,
      sortBy: 'created_at',
      search: '',
      currentPage: 1,
      total: 1,
      isDesc: false,
      params: {
        search: '',
        sortBy: 'created_at',
        sort: 'desc',
        page: 1
      },
      avisFields: [
        {key: 'id', label: '#', sortable: true},
        {key: 'name', label: 'name', sortable: true},
        {key: 'description', label: 'description', sortable: false},
        {key: 'timer', label: 'timer'},
        {
          key: 'created_at', thStyle: 'white-space: nowrap', sortable: true, formatter: createdAt => {
            return moment(createdAt).format('YYYY-MM-DD HH:mm')
          }
        },
        {key: 'actions', sortable: false}
      ]
    }
  },

  watch: {
    sortBy(data) {
      if (data) {
        this.params.sortBy = data;
        this.fetchAdsCampaigns();
      }
    },
    isDesc(data) {
      this.params.sort = (data === true ? 'desc' : 'asc')
      this.fetchAdsCampaigns();
    },
    search(data) {
      this.params.search = data;
      this.fetchAdsCampaigns()
    }
  },

  mounted() {
    this.fetchAdsCampaigns()
  },

  methods: {
    ...mapActions({
      createCampaign: 'dialogs/campaign/create',
      editCampaign: 'dialogs/campaign/edit'
    }),

    create() {
      this.createCampaign().then(() => {
        this.fetchAdsCampaigns()
      })
    },

    edit(id) {
      this.editCampaign(id).then(() => {
        this.fetchAdsCampaigns()
      })
    },

    fetchAdsCampaigns() {
      this.$api.adminAdsCampaigns.fetch(this.currentPage, this.params).then(response => {
        this.campaigns = response.data.data.map(item => {
          item.selected = false;
          return item;
        });
        this.currentPage = response.data.current_page;
        this.total = response.data.total;
        this.loading = false;
      })
    },

    remove() {
      this.$api.adminAdsCampaigns.delete(this.deletableId).then(response => {
        if (response.data.status === 'success') {
          this.fetchAdsCampaigns();
          this.deletableId = null;
        }
      })
    },

    bulkDelete() {
      this.$api.adminAdsCampaigns.bulkDelete(this.ids).then(() => {
        this.ids = [];
        this.fetchAdsCampaigns();
      });
    },

    showDeleteModal(id) {
      this.deletableId = id;
      this.$refs['deleteModal'].show()
    },

    handlePageChange(value) {
      this.params.page = value;
      this.fetchAdsCampaigns()
    }
  }
}
</script>
<style lang="scss">
.admin-avis {
  background: #24252d;
  padding: 25px;
  border-radius: 5px;
  margin-bottom: 100px;
}
</style>
