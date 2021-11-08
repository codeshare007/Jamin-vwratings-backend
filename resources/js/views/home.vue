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
                  <img class="img-preview" width="200" :src="'img/home/home-img-4.png'" alt data-toggle="modal" data-target="#myModal1">
                  <img class="img-preview" width="200" :src="'img/home/home-img-3.jpeg'" alt data-toggle="modal" data-target="#myModal2">
                </div>
                <div>
                  <img class="img-preview" width="200" :src="'img/home/home-img-2.jiff'" alt data-toggle="modal" data-target="#myModal3">
                  <img class="img-preview" width="200" :src="'img/home/home-img-1.jpeg'" alt data-toggle="modal" data-target="#myModal4">
                </div>
              </div>
            </div>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
    <div class="modal" id="myModal1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">      
          <!-- Modal body -->
          <div class="modal-body">
            <img src="img/home/home-img-4.png" width="900" height="700" alt/>        
          </div>
        </div>
      </div>
    </div>
    <div class="modal" id="myModal2">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">      
          <!-- Modal body -->
          <div class="modal-body">
            <img src="img/home/home-img-3.jpeg" width="900" height="700" alt/>        
          </div>
        </div>
      </div>
    </div>
    <div class="modal" id="myModal3">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">     
          <!-- Modal body -->
          <div class="modal-body">
            <img src="img/home/home-img-2.jiff" width="900" height="700" alt/>        
          </div>
        </div>
      </div>
    </div>
    <div class="modal" id="myModal4">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">      
          <!-- Modal body -->
          <div class="modal-body">
            <img src="img/home/home-img-1.jpeg" width="900" height="700" alt/>        
          </div>
        </div>
      </div>
    </div>
  </div>  
</template>
<script>
export default {
  data() {
    return {
      loading: false,
    };
  },
};
</script>
<style lang="scss">
.img-preview {
  width: 160px;
  height: 160px;
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

.img-preview:hover {
  opacity: 0.9;
}
.modal-body {
    text-align: center;
    background-color: #1a1d20;
}
</style>
