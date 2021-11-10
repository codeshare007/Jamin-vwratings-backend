<template>
  <div class="app" :style="{'background-image':'url(/img/tm-astro-bg.jpg)'}">
    <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"/>
    <router-view/>
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
    $route() {
   //   this.preloader();
    },
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
}
</style>
