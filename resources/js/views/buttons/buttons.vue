<template>
	   <div class="position-relative page-width-1 page-right">
			<div class="tm-bg-dark content-pad">
				<div class="text-center">
					<b-button :to="{name: 'ratings.specialone'}">Special</b-button>
					<b-button :to="{name: 'ratings.specialtwo'}">Special</b-button>
				</div>
				<hr>
				<div class="text-center">
					<b-button :to="{name: 'ratings.peepscreeps'}">Peeps&Creeps</b-button>
				</div>
				<hr>
				<div class="text-center">
					<b-button :to="{name: 'ratings.claim'}">Claim Name</b-button>
				</div>
				<hr>								
				<div class="text-center">
					<b-button :to="{name: 'ratings.contact'}">Message Us</b-button>
					<b-button :to="{name: 'ratings.info'}">Info</b-button>
				</div>
				<hr>
				<div class="text-center">
					<b-button :to="{name: 'ratings.home'}">Home</b-button>
					<b-button :to="{name: 'ratings.forum'}">Forum</b-button>								
				</div>
			</div>
		</div>
</template>
<style lang="scss">
.btn-secondary {
    color: #fff;
    background-color: transparent;
    border-color: #99ff66;
	font-size: 1.5rem;
}

.btn-secondary:hover {
    color: #57bd25;
    background-color: #ffffff;
    border-color: #57bd25;
}
</style>
