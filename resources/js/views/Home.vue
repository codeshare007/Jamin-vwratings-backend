<template>
  <div class="homePage">

    <b-card bg-variant="dark" text-variant="white">
      <h1 class="text-center">Welcome to vwRatings</h1>
      <h2 class="text-center">Behaviour ratings of vw characters to highlight the awesome and expose the rotten.</h2>

      <div class="d-flex justify-content-between mt-5">
        <div class="infoCard">1000s of player names</div>
        <div class="infoCard">1000s of comments & pics</div>
        <div class="infoCard">1000s of ratings</div>
      </div>

      <div class="mt-4">
        <p class="text-center m-0">Rate and comment about other vw players that you think are exceptional or just plain
          trouble.</p>
        <p class="text-center">Everything is anonymous....</p>
      </div>

      <div class="d-flex justify-content-center">
        <div class="homePage__gallery">
          <viewer>
            <img class="homePage__image" alt="Image 1" src="img/home/home-img-4.png" />
            <img class="homePage__image" alt="Image 2" src="img/home/home-img-3.jpeg" />
            <img class="homePage__image" alt="Image 3" src="img/home/home-img-2.jiff" />
            <img class="homePage__image" alt="Image 4" src="img/home/home-img-1.jpeg" />
          </viewer>
        </div>
      </div>
    </b-card>
  </div>
</template>
<style lang="scss">
.homePage {

  h1 {
    font-size: 28px;
  }

  h2 {
    font-size: 20px;
  }

  .infoCard {
    background: #29413b;
    text-align: center;
    font-size: 18px;
    max-width: 32%;
    flex: 0 0 32%;
  }

  &__gallery {
    width: 345px;
  }

  &__image {
    cursor: pointer;
    margin: 5px;
    width: 160px;
    height: 160px;
  }
}
</style>
