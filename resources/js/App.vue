<template>
  <div class="app">
    <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"/>
    <router-view/>
  </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
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
    $route() {
      this.preloader();
    },
  },

  components: {
    Loading
  },

  computed: {
    ...mapState('auth', {
      loggedIn: state => state.loggedIn,
    }),
  },

  methods: {
    preloader() {
      this.isLoading = true;
      setTimeout(() => {
        this.isLoading = false
      }, 500)
    },
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}

.vld-overlay .vld-background {
  opacity: 1;
}

.app {
  background-color: #15161c;
}
</style>
