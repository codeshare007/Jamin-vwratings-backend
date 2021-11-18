<template>
  <div class="adminParties">
    <AdminTable
      :fields="partiesFields"
      entity="party"
      entities="parties"
    />
  </div>
</template>
<script>
  import AdminTable from "../../components/admin/AdminTable";
  import moment from "moment";
  export default {
    components: {
      AdminTable
    },

    data() {
      return {
        partiesFields: [
          {key: 'select', label: '', sortable: false},
          {key: 'id', label: '#', sortable: true},
          {key: 'username', sortable: true},
          {
            key: 'email', formatter: item => {
              return item ? item : '—'
            }, sortable: true
          },
          {key: 'role', sortable: true},
          {
            key: 'created_at', thStyle: 'white-space: nowrap', sortable: true, formatter: createdAt => {
              return moment(createdAt).format('YYYY-MM-DD HH:mm')
            }
          },
          {key: 'actions', sortable: false}
        ]
      }
    }
  }
</script>
