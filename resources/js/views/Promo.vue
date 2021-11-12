<template>
  <div class="promoPage">
    <div class="promoPage__header">
      <h4 class="mt-3 mb-5">Continue stalking in {{ timerCount }} seconds</h4>

      <hr class="bg-white">
    </div>
    <div class="promoPage__body">
      <iframe width="100%" height="700px" src="https://www.youtube.com/embed/mowYouYRscw" title="YouTube video player"
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen></iframe>
    </div>
  </div>
</template>
<script>
import Cookie from "js-cookie";

export default {
  data() {
    return {
      timerCount: 10
    }
  },

  watch: {
    timerCount: {
      handler(value) {
        if (value > 0) {
          setTimeout(() => {
            this.timerCount--;
          }, 1000);
        } else {
          Cookie.set('promo', 1);
          if (Cookie.get('last_page')) {
            let lastPage = Cookie.get('last_page');
            Cookie.remove('last_page')
            this.$router.push({ path: lastPage })
          } else {
            this.$router.push({ name: 'ratings.home' })
          }

        }
      },
      immediate: true
    }
  },


  mounted() {
    //Cookie.set('promo', 1)
  }
}
</script>
<style lang="scss">
.promoPage {
  height: 100vh;
  background: rgb(44 71 92);

  &__header {
    padding: 20px;
    color: white;
  }
}
</style>
