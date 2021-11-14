<template>
  <transition name="fade">
    <div class="entitiesList" v-if="screenLoaded">
      <b-modal ref="createModal" ok-title="Add" size="lg" ok-variant="dark" @ok="create" @cancel="clear" title="Add a Name">
        <b-form>
          <b-row class="justify-content-center">
            <b-col cols="6">
              <b-form-group label="Name">
                <b-form-input v-mask="mask" type="text" placeholder="Enter Name" v-model="form.name"/>
              </b-form-group>
            </b-col>
          </b-row>

          <b-row v-if="form.name">
            <b-col v-for="(item, key) in this.suggestions" :key="key" cols="4">
              <div>
                {{ item.name }}
              </div>
            </b-col>
          </b-row>

        </b-form>
      </b-modal>

      <b-modal ref="notRegistered" ok-only ok-title="Close" ok-variant="secondary" title="No no no !!!">
        <div>You must log in first.</div>
      </b-modal>

      <b-row class="d-flex justify-content-center mb-1">
        <div class="d-flex">
          <button class="entitiesList__button mr-2" @click="showCreateDialog">Add name</button>
        </div>
      </b-row>

      <b-row class="d-flex justify-content-center">
        <div class="d-flex justify-content-between">
          <b-form-select v-model="type" class="mr-2">
            <b-form-select-option
              :key="key"
              v-for="(item, key) in this.types"
              :value="key"
              v-html="item"
            />
          </b-form-select>
          <input
            class="form-control mb-3"
            placeholder="Search..."
            type="text"
            v-model="search"
          >
        </div>
      </b-row>

      <div class="namers d-flex align-items-center flex-column">
        <router-link
          class="d-block"
          v-for="(item, i) in this.items" :key="i"
          :to="redirectToItem(item.id)"
          v-html="item.name"
        />
      </div>

      <div v-if="items.length === 0">
        <p class="text-center">No name matches your search... you can add it.</p>
      </div>

      <div v-if="loading" class="d-flex justify-content-center mt-3 align-items-center" style="min-height: inherit;">
        <b-spinner />
      </div>
    </div>
  </transition>
</template>
<script>
export default {
  props: {
    entity: String,
    method: String
  },
  data() {
    return {
      items: [],
      suggestions: [],
      search: null,
      currentPage: 1,
      loading: true,
      screenLoaded: false,
      type: 'full_list',
      types: {
        full_list: 'All',
        good_list: 'Good',
        bad_list: 'Bad',
        comments: 'Comments',
        pics: 'Pics'
      },
      form: {
        name: null
      },
      params: {
        type: 'full_list',
        per_page: 50
      },
      mask: {
        mask: 'SSSSSSSSSSSSSSSS',
        tokens: {'S': {pattern: /[0-9a-zA-Z ]/}}
      },
    }
  },

  computed: {
    loggedIn() {
      return this.$store.getters['auth/loggedIn']
    }
  },

  watch: {
    'form.name'(value) {
      console.log(value.length);
      if (value.length >= 2) {
        this.searchItems();
      }
    },
    search(data) {
      if (data) {
        this.params.search = data;
        this.fetchItems();
      } else {
        delete this.params.search;
        this.fetchItems();
      }
    },
    type(data) {
      this.currentPage = 1;
      this.params.type = data;
      this.items = [];
      this.fetchItems();
    }
  },

  mounted() {
    this.fetchItems();
    this.lazyLoad();
  },

  methods: {
    redirectToItem(id) {
      const routeName = `ratings.${this.method}.view`
      return {name: routeName, params: {id: id}}
    },

    create() {
      this.$api[this.method].create(this.form).then(response => {
        const item = response.data.data;
        this.$router.push({name: `ratings.${this.method}.view`, params: {id: item.id}})
      })
    },

    searchItems() {
      this.$api[this.method].fetch(1, {search: this.form.name, per_page: 5000}).then(response => {
        this.suggestions = response.data
      })
    },

    fetchItems(lazy = false) {
      this.loading = true;
      this.$api[this.method].fetch(this.currentPage, this.params).then(response => {
        lazy ? this.items = [...this.items, ...response.data] : this.items = response.data;
        if (!lazy) this.screenLoaded = true;
        this.loading = false;
      })
    },

    showCreateDialog() {
      this.loggedIn ? this.$refs['createModal'].show() : this.$refs['notRegistered'].show()
    },

    clear() {
      this.form.name = ''
    },

    lazyLoad() {
      window.onscroll = () => {
        let bottomOfWindow = (document.documentElement.scrollTop + window.innerHeight) ===
          document.documentElement.offsetHeight;

        if (bottomOfWindow) {
          if (this.$route.name === `ratings.${this.method}.list`) {
            this.currentPage = this.currentPage + 1;
            this.loading = true;
            this.fetchItems(true)
          }
        }
      }
    }
  }
}
</script>
<style lang="scss">
.entitiesList {
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
</style>
