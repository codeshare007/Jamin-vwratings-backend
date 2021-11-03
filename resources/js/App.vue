<template>
  <div class="app">
    <div class="dialog-auth" v-if="loggedIn">
      <FieldEditDialog />
    </div>
    <router-view />
  </div>
</template>

<script>
import {mapState} from "vuex";
import FieldEditDialog from "./components/dialogs/FieldEditDialog";

export default {
  name: 'App',
  components: {
    FieldEditDialog
  },
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
</style>
