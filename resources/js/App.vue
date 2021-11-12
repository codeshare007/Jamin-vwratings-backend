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
import Cookie from "js-cookie";
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
      if (Cookie.get('promo')) {
        let amount = Cookie.get('promo');
        if (amount > 10) {
          Cookie.set('last_page', this.$route.path);
          window.location.href = '/promo';
        }
      }
    }
  },

  computed: {
    ...mapState('auth', {
      loggedIn: state => state.loggedIn,
    }),
  },

  mounted() {

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

body {
  min-height: 100vh;
}

.app {
  background-attachment: fixed;
  background-repeat: no-repeat;
  background-size: cover;
  background-image: url('/img/background.jpg');
}
</style>
