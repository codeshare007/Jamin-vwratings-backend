<template>
  <div class="admin-comments">
    <b-pagination
      v-model="currentPage"
      @change="handlePageChange"
      :total-rows="total"
    />
    <b-table
      table-variant="dark"
      :items="comments"
      :fields="commentsFields"
    >
      <template #cell(index)="data">{{ data.index + 1 }}</template>
      <template #cell(opinion)="data">{{ data.item.opinion | opinion }}</template>
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
import moment from "moment";

export default {
  data() {
    return {
      total: 1,
      currentPage: 1,
      comments: [],
      commentsFields: [
        {key: 'select', label: ''},
        {key: 'index', label: '#'},
        {key: 'user.username', label: 'user'},
        {key: 'avi.name', label: 'avi'},
        {key: 'content'},
        {key: 'opinion'},
        {
          key: 'created_at', formatter: createdAt => {
            return moment(createdAt).format('YYYY-MM-DD HH:mm')
          }, thStyle: 'min-width: 100px'
        },
        {
          key: 'updated_at', formatter: createdAt => {
            return moment(createdAt).format('YYYY-MM-DD HH:mm')
          }, thStyle: 'min-width: 110px'
        },
        {key: 'actions', thStyle: 'min-width: 120px'}
      ]
    }
  },

  filters: {
    opinion(data) {
      if (data !== null) {
        return data === 1 ? 'positive' : 'negative';
      }

      return '—'
    }
  },

  mounted() {
    this.fetchComments(1)
  },

  methods: {
    fetchComments(page) {
      this.$api.adminComments.fetch(page).then(response => {
        this.comments = response.data.data;
        this.total = response.data.total;
      })
    },
    handlePageChange(value) {
      this.fetchComments(value)
    }
  }
}
</script>
<style lang="scss">
  .admin-comments {
    background: #24252d;
    padding: 25px;
    border-radius: 5px;
    margin-bottom: 100px;
  }
</style>
