<template>
  <div class="entityInfo">
    <b-button class="back float-left mt-2" :to="{ name: screen }">Back</b-button>
    <p class="entityInfo__name">{{ name }}</p>
    <div class="entityInfo__rating">
      <star-rating
        :max-rating="12"
        :increment="0.5"
        :read-only="true"
        :rating="averageRating"
      />
    </div>
    <hr>
  </div>
</template>
<script>
export default {
  props: {
    screen: String,
    name: String,
    averageRating: {}
  }
}
</script>
<style lang="scss">
.entityInfo {
  text-align: center;
  font-family: 'Futura PT', sans-serif;
  font-size: 24px;

  p {
    margin-bottom: 0;
  }


  @media screen and (min-width: 1024px) {
    padding: 0 40px;
  }

  &__rating {
    display: flex;
    width: 100%;
    justify-content: center;
    justify-items: center;
  }

  .vue-star-rating-star {
    width: 20px;

    @media screen and (min-width: 1024px) {
      width: 30px;
    }
  }

  &__name {
    font-size: 30px;
    color: #23e116;
  }

}
</style>
