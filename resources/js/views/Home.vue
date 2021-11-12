<template>
  <div class="page-container text-center">
    <div class="container-fluid tm-content-container">
      <div class="position-relative page-width-1 page-right">
        <div class="tm-bg-dark content-pad">
          <h3 class="mb-4">
            Welcome to vwRatings<br><span style="font-size:20px">Behaviour ratings of vwcharacters to highlight the awesome and expose the rotten.</span></h3>

          <div class="content">
            <div class="row row-deck">
              <div class="col-md-6 col-xl-4">
                <div class="block block-rounded">
                  <div class="block-content fs-sm texting">
                    <p>
                      1000s of player names
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-4">
                <div class="block block-rounded">
                  <div class="block-content fs-sm texting">
                    <p>
                      1000s of comments & pics
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-4">
                <div class="block block-rounded">
                  <div class="block-content fs-sm texting">
                    <p>
                      1000s of ratings
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <p class="mb-0">
            Rate and comment about other vw players that you think are exceptional or just plain
            trouble.<br> Everything is anonymous....</p>
          <div class="d-flex justify-content-center">
            <div class="homePage__gallery">
              <viewer>
                <img class="homePage__image" alt="Image 1" src="img/home/home-img-4.png"/>
                <img class="homePage__image" alt="Image 2" src="img/home/home-img-3.jpeg"/>
                <img class="homePage__image" alt="Image 3" src="img/home/home-img-2.jiff"/>
                <img class="homePage__image" alt="Image 4" src="img/home/home-img-1.jpeg"/>
              </viewer>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style lang="scss">
.texting {
  background: #1c4a1d;
}

.viewer-backdrop {
  background-color: rgba(0, 0, 0, 1);
}

.homePage {


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
