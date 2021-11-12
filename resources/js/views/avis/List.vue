<template>
 <transition name="fade">
   <div class="aviList" v-if="screenLoaded">
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
       title="No no no !!!">You must log in first.
     </b-modal>
     <b-row class="d-flex justify-content-center mb-1">
       <div class="d-flex">
         <button class="aviList__button mr-2" @click="showAviDialog">Add name</button>
       </div>
     </b-row>

     <b-row class="d-flex justify-content-center">
       <div class="d-flex justify-content-between">
         <b-form-select v-model="type" class="mr-2">
           <b-form-select-option value="full_list">All</b-form-select-option>
           <b-form-select-option value="good_list">Good</b-form-select-option>
           <b-form-select-option value="bad_list">Bad</b-form-select-option>
           <b-form-select-option value="comments">Comments</b-form-select-option>
           <b-form-select-option value="pics">Pics</b-form-select-option>
         </b-form-select>
         <input class="form-control mb-3" placeholder="Search..." type="text" v-model="search">
       </div>
     </b-row>

     <div class="namers d-flex align-items-center flex-column">
       <router-link
         v-for="(avi, key) in this.avis" :key="key"
         class="d-block"
         :to="{name: 'ratings.avis.view', params: {id: avi.id}}"
         v-html="avi.name"
       />
     </div>

     <div v-if="loading" class="d-flex justify-content-center mt-3 align-items-center" style="min-height: inherit;">
       <b-spinner />
     </div>
   </div>
 </transition>
</template>
<script>
export default {

  data() {
    return {
      avis: [],
      search: null,
      currentPage: 1,
      loading: true,
      screenLoaded: false,
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
    search(data) {
      if (data) {
        this.params.search = data;
        this.fetchAvis();
      }
    },
    type(data) {
      this.currentPage = 1;
      this.params.type = data;
      this.avis = [];
      this.fetchAvis();
    }
  },

  mounted() {
    this.fetchAvis();
    this.lazyLoad();
  },

  methods: {
    fetchAvis() {
      this.loading = true;
      this.$api.avis.fetch(this.currentPage, this.params).then(response => {
        this.avis = response.data;
        this.loading = false;
        this.screenLoaded = true;
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
          (document.documentElement.scrollTop + window.innerHeight) ===
          document.documentElement.offsetHeight;

        if (bottomOfWindow) {
          if (this.$route.name === 'ratings.avis.list') {
            this.loading = true;
            this.currentPage = this.currentPage + 1;
            this.$api.avis.fetch(this.currentPage, this.params).then(response => {
              const avis = response.data;
              for (let i in avis) this.avis.push(avis[i]);
              this.loading = false
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
  background: black;
  padding-top: 40px;
  margin-bottom: 40px;
  padding-bottom: 40px;
  display: block;

  @media screen and (min-width: 1024px) {
    margin-right: 150px;
    margin-left: 150px;
  }

  &__button {
    background: #1c4a1d;
    color: white;
    border: 0;
    padding: 5px 15px;
    margin-bottom: 5px;
    border-radius: 5px;
  }

  a {
    color: #ffffff;
    font-size: 20px;

    &:hover {
      color: #02cf4a;
    }
  }
}

/*
.custom-select {
  height: calc(1.5em + 0.75rem - 4px);
}

.form-control {
  height: calc(1.5em + 0.75rem - 4px);
}

.tm-bg-dark {
  min-height: 300px;
}

.namers {
  width: fit-content;
  margin: auto;
}*/
</style>
