<template>
  <div class="dashboard">
    <b-row>
      <b-col cols="12">
        <b-card class="mb-3" bg-variant="dark" text-variant="white">
          <b-card-body class="d-flex justify-content-center">
            <div class="text-center w-100">
              <h3 class="mb-4">Welcome to vwRatings<br>
                <span style="font-size:20px">
				          Behaviour ratings of vw characters to highlight the awesome and expose the rotten.
				        </span>
              </h3>
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
                trouble.<br> Everything is anonymous....
              </p>
              <div class="intro-right mt-2" v-if="!loading">
                <div>
                  <img class="img-preview" width="200" :src="'img/home/home-img-4.png'" alt>
                  <img class="img-preview" width="200" :src="'img/home/home-img-3.jpeg'" alt>
                </div>
                <div>
                  <img class="img-preview" width="200" :src="'img/home/home-img-2.jiff'" alt>
                  <img class="img-preview" width="200" :src="'img/home/home-img-1.jpeg'" alt>
                </div>
              </div>
            </div>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>
<style lang="scss">

</style>
