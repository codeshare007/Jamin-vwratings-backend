<template>
  <div class="app">
    <router-view />
  </div>
</template>

<script>
import {mapState} from "vuex";
export default {
  name: 'App',
  computed: {
    ...mapState('auth', {
      loggedIn: state => state.loggedIn,
    }),
  },
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

.app {
  background-color: #15161c;
}
</style>
