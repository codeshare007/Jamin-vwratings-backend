<template>
  <div class="default-theme">
    <HeaderBar />
    <b-container class="p-0">
      <router-view />
    </b-container>
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
    width: 100%;
    @media screen and (min-width: 768px) {
      width: calc(100% - 240px);
      padding-left: 20px
    }
  }

  &__container {
    height: calc(100% - 100px);
    display: flex;
    justify-content: flex-start;
  }
}
</style>
