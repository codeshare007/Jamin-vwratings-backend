<template>
  <div class="avi-list">

    <b-row class="d-flex justify-content-center">
      <div class="w-50 d-flex justify-content-between pt-5">
        <b-button variant="success" class="w-25">Create</b-button>
        <b-form-select class="w-25" v-model="type">
          <b-form-select-option value="full_list">Full list</b-form-select-option>
          <b-form-select-option value="good_list">Good list</b-form-select-option>
          <b-form-select-option value="bad_list">Bad list</b-form-select-option>
          <b-form-select-option value="recent_list">Recent list</b-form-select-option>
          <b-form-select-option value="most_rated">Most rated</b-form-select-option>
          <b-form-select-option value="comments">Comments</b-form-select-option>
          <b-form-select-option value="pics">Pics</b-form-select-option>
        </b-form-select>
        <input class="w-25 form-control" placeholder="Search..." type="text" v-model="query">
      </div>
    </b-row>

    <b-card style="min-height: 500px">
      <b-card-body class="d-flex align-items-center flex-column">
        <router-link
          :key="key"
          class="d-block"
          :to="{name: 'ratings.avis.view', params: {id: avi.id}}"
          v-for="(avi, key) in this.avis">
          {{ avi.name }}
        </router-link>
      </b-card-body>
    </b-card>
  </div>
</template>
<script>
export default {

  data() {
    return {
      query: null,
      currentPage: 1,
      avis: [],
      type: 'full_list'
    }
  },

  watch: {
    query(data) {
      if (data) {
        this.$api.avis.fetch({query: data, per_page: 100}).then(response => {
          this.avis = response;
        })
      } else {
        this.fetchAvis()
      }
    },
    type(data) {
      this.$api.avis.fetch({type: data, per_page: 100}).then(response => {
        this.avis = response;
      })
    }
  },

  mounted() {
    this.fetchAvis();
    this.fetchNextAxis();
  },

  methods: {
    fetchAvis() {
      this.$api.avis.fetch({per_page: 50}).then(response => {
        this.avis = response;
      })
    },
    fetchNextAxis() {
      window.onscroll = () => {
        let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight ===
          document.documentElement.offsetHeight;
        if (bottomOfWindow) {
          if (this.$route.name === 'ratings.avis.list') {
            this.currentPage++
            this.$api.avis.fetch({page: this.currentPage, per_page: 50}).then(response => {
              for (let i in response) {
                this.avis.push(response[i])
              }
            })
          }
        }
      }
    }
  }
}
</script>
<style lang="scss">
.avi-list {
  background: black;
  border-radius: 5px;

  p {
    color: white;
  }

  .card {
    background: transparent;

    a {
      color: white;
    }
  }
}
</style>
