<template>
  <div class="app">
    <loading
      :active.sync="isLoading"
      :can-cancel="true"
      :is-full-page="fullPage"
    />
    <router-view />
  </div>
</template>

<script>
import {mapState} from "vuex";
export default {
  name: 'App',
  data() {
    return {
      isLoading: false,
      fullPage: true
    }
  },

  watch: {
    '$route'(to, from) {
      axios.post('/api/v1/tracker', {
        last_page: this.$route.path,
        route: this.$route.name
      }).then(response => {
        if (response.data['start_promo']) {
          window.location.href = '/promo';
        }
      }).catch(error => {
        if (error) {
          window.location.href = '/promo'
        }
      });
    }
  },

  computed: {
    ...mapState('auth', {
      loggedIn: state => state.loggedIn,
    }),
  },

  methods: {
    preloader() {
      this.preloader();
      this.isLoading = true;
      setTimeout(() => {
        this.isLoading = false
      }, 500)
    },
  }
}
</script>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}

.app {
  background-attachment: fixed;
  background-repeat: no-repeat;
  background-image: url('/img/background.jpg');
}
</style>
