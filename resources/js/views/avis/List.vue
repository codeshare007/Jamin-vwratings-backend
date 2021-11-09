<template>
  <div class="aviList">

    <b-modal
      ref="createAvi"
      ok-title="Add"
      ok-variant="dark"
      @ok="createAvi"
      @cancel="form.name = ''"
      title="Add a Name">
      <b-form>
        <b-form-group label="">
          <b-form-input v-mask="mask" type="text" placeholder="Enter Name" v-model="form.name"/>
        </b-form-group>
      </b-form>
    </b-modal>

    <b-modal
      ref="notRegistered"
      ok-only
      ok-title="Close"
      ok-variant="secondary"
      title="Wait">You must be logged in to perform this action
    </b-modal>

    <b-row class="d-flex justify-content-center">
      <div class="w-50 d-flex justify-content-between pt-5">
        <button class="aviList__button mr-2" @click="showAviDialog">{{ loggedIn ? 'Add' : 'Login to add' }}</button>
        <b-form-select v-model="type" class="mr-2">
          <b-form-select-option value="full_list">All</b-form-select-option>
          <b-form-select-option value="good_list">Good</b-form-select-option>
          <b-form-select-option value="bad_list">Bad</b-form-select-option>
          <b-form-select-option value="comments">Comments</b-form-select-option>
          <b-form-select-option value="pics">Pics</b-form-select-option>
        </b-form-select>
        <input class="form-control" placeholder="Search..." type="text" v-model="search">
      </div>
    </b-row>

    <b-card style=""> <!-- min-height: 500px -->
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
      avis: [],
      search: null,
      currentPage: 1,
      type: 'full_list',
      form: {
        name: null
      },
      params: {
        type: 'full_list',
        per_page: 50
      },
      mask: {
        mask: 'SSSSSSSSSSSSSSSS',
        tokens: {
          'S': {
            pattern: /[0-9a-zA-Z ]/,
          }
        }
      },
    }
  },

  computed: {
    loggedIn() {
      return this.$store.getters['auth/loggedIn']
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
    search(data) {
      this.params.search = data;
      this.fetchAvis();
    },
    type(data) {
      this.currentPage = 1;
      this.params.type = data;
      this.fetchAvis();
    }
  },

  mounted() {
    this.fetchAvis();
    this.lazyLoad();
  },

  methods: {
    fetchAvis() {
      this.$api.avis.fetch(this.currentPage, this.params).then(response => {
        this.avis = response.data;
      })
    },

    showAviDialog() {
      this.loggedIn ? this.$refs['createAvi'].show() : this.$refs['notRegistered'].show()
    },

    createAvi(e) {
      e.preventDefault();
      this.$api.avis.create(this.form).then(response => {
        const avi = response.data.data;
        this.$router.push({name: 'ratings.avis.view', params: {id: avi.id}})
      })
    },


    lazyLoad() {
      window.onscroll = () => {
        let bottomOfWindow =
          document.documentElement.scrollTop + window.innerHeight ===
          document.documentElement.offsetHeight;

        if (bottomOfWindow) {
          if (this.$route.name === 'ratings.avis.list') {
            this.currentPage = this.currentPage + 1;
            this.$api.avis.fetch(this.currentPage, this.params).then(response => {
              const avis = response.data;
              for (let i in avis) {
                this.avis.push(avis[i]);
              }
            });
          }
        }
      }
    }
  }
}
</script>
<style lang="scss">
.aviList {
  background: #24252d;
  border-radius: 5px;

  &__button {
    width: 600px;
    border: 0;
    border-radius: 5px;
    background: #40685d;
    color: white;

    &:hover {
      transition: .3s;
      background: #29413b;
    }

    &:focus {
      outline: 0;
    }
  }

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
