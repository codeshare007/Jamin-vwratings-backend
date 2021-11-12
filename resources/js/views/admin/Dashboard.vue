<template>
  <div class="adminDashboard">
    <h1 class="mt-5">Welcome back, {{ profile.username }}!</h1>
    <b-row v-if="!loading">
      <b-col>
        <b-card class="mt-5" header="Users" text-variant="white" bg-variant="dark">
          <h1>{{ information.users }}</h1>
        </b-card>
      </b-col>
      <b-col>
        <b-card class="mt-5" header="Comments" text-variant="white" bg-variant="dark">
          <h1>{{ information.comments }}</h1>
        </b-card>
      </b-col>
      <b-col>
        <b-card class="mt-5" header="Ratings" text-variant="white" bg-variant="dark">
          <h1>{{ information.ratings }}</h1>
        </b-card>
      </b-col>
      <b-col>
        <b-card class="mt-5" header="Avis" text-variant="white" bg-variant="dark">
          <h1>{{ information.avis }}</h1>
        </b-card>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="3">
        <b-form class="mt-5">
          <b-form-group label="Amount of hits to see promo page" class="text-white">
            <b-form-input type="number" class="bg-dark text-white border-dark" v-model="form.hits" />
          </b-form-group>

          <b-button @click="changeAmount()">Change</b-button>
        </b-form>
      </b-col>
    </b-row>
  </div>
</template>
<script>
export default {
  data() {
    return {
      information: {},
      loading: true,
      form: {
        hits: null
      }
    }
  },

  computed: {
    profile() {
      return this.$store.getters['auth/profile']
    }
  },

  mounted() {
    this.fetchInformation();
    this.fetchHits();
  },

  methods: {
    fetchInformation() {
      this.$api.adminDashboard.information().then(response => {
        this.information = response.data;
        this.loading = false;
      })
    },

    fetchHits() {
      this.$api.adminDashboard.hits().then(response => {
        this.form.hits = response.data;
      })
    },

    changeAmount() {
      this.$api.adminDashboard.changeHits(this.form.hits).then(response => {
        if (response.data.status === 'success') {
          this.fetchHits();
        }
      })
    }
  }
}
</script>
<style lang="scss">
.adminDashboard {
  background: #24252d;
  padding: 25px;
  border-radius: 5px;
  margin-bottom: 100px;

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
