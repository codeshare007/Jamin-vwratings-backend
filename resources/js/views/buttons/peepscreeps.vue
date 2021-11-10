<template>
                       <div class="position-relative page-right">
                            <div class="tm-bg-dark content-pad text-center">
                                <p class="mb-4">
                                   Nominate and vote on your favorite players and the worst players you know.<br>Peeps will get treats.<br>Creeps are just creeps.
                                </p>
                                <h2 class="mb-4">
                                  COMING SOON 
                                </h2>								
                                <div class="sidey text-center">
                                    <b-button :to="{name: ''}">Peeps</b-button>
                                    <br>
									Peeps = Decent players
                                </div>
                                <div class="sidey text-center">
                                    <b-button :to="{name: ''}">Creeps</b-button>
									<br>
									Creeps = Not so decent
                                </div>
                            </div>
                        </div>
</template>
<script>
  export default {

  }
</script>
<style lang="scss">

.btn-secondary {
    color: #fff;
    background-color: transparent;
    border-color: #99ff66;
	font-size: 1rem;
}

.btn-secondary:hover {
    color: #57bd25;
    background-color: #ffffff;
    border-color: #57bd25;
}

.sidey {
    display: inline-block;
    border: none;
    padding: 1rem 1rem;
    vertical-align: middle;
    width: 45%;
}

.sidey {
    width: 100%;
}

  .peepscreeps-page {

  }
</style>