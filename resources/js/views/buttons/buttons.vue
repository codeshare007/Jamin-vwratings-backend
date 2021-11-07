<template>
  <div class="buttons">
    <b-row>
      <b-col cols="12">
        <b-card class="mb-3" bg-variant="dark" text-variant="white">
          <b-card-body class="justify-content-center">
		   <div class="text-center">
				<b-button :to="{name: 'ratings.1special'}">Special</b-button>
				<b-button :to="{name: 'ratings.2special'}">Special</b-button>
			</div>
			<hr>
			<div class="text-center">
				<b-button :to="{name: 'ratings.peepcreep'}">Peeps&Creeps</b-button>
			</div>
			<hr>
			<div class="text-center">
				<b-button :to="{name: 'ratings.claim'}">Claim Name</b-button>
			</div>
			<hr>								
			<div class="text-center">
				<b-button :to="{name: ''}">Message Us</b-button>
				<b-button :to="{name: 'ratings.info'}">Info</b-button>
			</div>
			<hr>
			<div class="text-center">
				<b-button :to="{name: 'ratings.dashboard'}">Home</b-button>
				<b-button :to="{name: 'ratings.forum'}">Forum</b-button>								
			</div>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>

