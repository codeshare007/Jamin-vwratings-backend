<template>
  <div class="default-theme">
    <HeaderBar />
    <div class="default-theme__main">
      <b-container class="p-0">
        <router-view />
      </b-container>
    </div>
  </div>
</template>
<script>
import HeaderBar from '../components/layout/HeaderBar';
export default {
  components: {
    HeaderBar,
  }
}
</script>
<style lang="scss">
.default-theme {
  min-height: 100vh;

  &__main {
    padding-bottom: 100px;
  }
}
</style>
