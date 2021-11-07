<template>
  <div class="admin-dashboard">
    <h1>Welcome to Admin Dashboard</h1>
    <b-row v-if="!loading">
      <b-col>
        <b-card class="mt-5" header="Users" text-variant="white" bg-variant="dark">
          <h1>{{information.users}}</h1>
        </b-card>
      </b-col>
      <b-col>
        <b-card class="mt-5" header="Comments" text-variant="white" bg-variant="dark">
          <h1>{{information.comments}}</h1>
        </b-card>
      </b-col>
      <b-col>
        <b-card class="mt-5" header="Ratings" text-variant="white" bg-variant="dark">
          <h1>{{information.ratings}}</h1>
        </b-card>
      </b-col>
      <b-col>
        <b-card class="mt-5" header="Avis" text-variant="white" bg-variant="dark">
          <h1>{{information.avis}}</h1>
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>
<script>
  export default {
    data() {
      return {
        information: {},
        loading: true
      }
    },

    mounted() {
      this.fetchInformation()
    },

    methods: {
      fetchInformation() {
        this.$api.adminDashboard.information().then(response => {
          this.information = response.data;
          this.loading = false;
        })
      }
    }
  }
</script>
<style lang="scss">
  .admin-dashboard {
    padding-left: 100px;
    padding-right: 100px;

    h1 {
      color: white;
      text-align: center;
      font-family: 'Futura PT', sans-serif;
    }

    .information {
      font-size: 24px;
    }
  }
</style>
