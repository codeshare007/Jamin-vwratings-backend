<template>
  <div class="default-theme">
    <HeaderBar class="mb-3" />
    <b-container class="default-theme__main p-0">
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
	color: white;

  &__main {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }
}
</style>
