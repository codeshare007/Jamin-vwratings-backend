<template>
  <div class="info-page">
						
							<div class="row">
							  <div class="column">
								<div class="card">
								 <div class="topy"> What can you do here </div>
								  <div class="container first">
									<ul>
									  <li>Rate game players</li>
									  <li>Talk about players</li>
									  <li>Claim your own player</li>
									  <li>Rate & talk about parties</li>
									  <li>Nominate & vote best players</li>	
									  <li>Nominate & vote worst players</li>
									  <li>Vent</li>									  
									</ul> 
								  </div>
								</div>
							  </div>

							  <div class="column">
								<div class="card">
								  <div class="topy"> Rate other players </div>
								  <div class="container">
									<p>Show others what you think of different players. Rate them from 1 to 12. Your rating will average in with other's ratings and we will see what the players are really like.</p>
									Things aren't always as they seem
								  </div>
								</div>
							  </div>
							  
							  <div class="column">
								<div class="card">
								  <div class="topy"> Comment on other players </div>
								  <div class="container">
									<p>Voice your opinions and let others know about your encounters with other players.<br>Btw--It's much more useful to have substance in your comments instead of a couple of derogatory remarks.</p>
									Answer the why.
								  </div>
								</div>
							  </div>
							  <div class="column">
								<div class="card">
								  <div class="topy"> Claim your name </div>
								  <div class="container">
									<p>Protect your little character's name from unproven comments. But it doesn't protect you from pictures showing you being a bad little avi.</p>
									Don't be one of those players that takes pride in being a total idiot.
								  </div>
								</div>
							  </div>

							  <div class="column">
								<div class="card">
								  <div class="topy"> Rate party throwers </div>
								  <div class="container">
									<p>Let others know who throws the best parties or the worst. Which parties are fun and creative. Tell others about bad experiences while partying.</p>
									Announce upcoming party events if you like.
								  </div>
								</div>
							  </div>
							  
							  <div class="column">
								<div class="card">
								  <div class="topy"> Vote for best and worst players </div>
								  <div class="container">
									<p>Nominate and vote for the best players and the worst. We will showcase them so everyone knows who to enjoy or block.</p>
									It may be a game but respect is still key.
								  </div>
								</div>
							  </div>
							</div>							
                            </div>
                        </div>
  </div>
</template>
<script>
  export default {

  }
</script>
<style lang="scss">
  .info-page {
.column {
  float: left;
  width: 50%;
  margin-bottom: 16px;
  padding: 0 8px;
}

.card {
    background-color: transparent;
    border: 1px solid #99ff6666;
    border-radius: .25rem;
	margin: 8px;
	min-height: 300px;
}

.about-section {
  padding: 50px;
  text-align: center;
  background-color: #474e5d;
  color: white;
}

.container {
  padding: 0 16px;
}

.container::after, .row::after {
  content: "";
  clear: both;
  display: table;
}

.title {
  color: grey;
}

.button {
  border: none;
  outline: 0;
  display: inline-block;
  padding: 8px;
  color: white;
  background-color: #000;
  text-align: center;
  cursor: pointer;
  width: 100%;
}

.button:hover {
  background-color: #555;
}

hr {
    margin: 0.3rem 0;
}

.topy {
  background-color: #1c4a1d;
  margin-bottom: 7px;
}

.first {
	text-align: left;
}

@media screen and (max-width: 768px) {
  .column {
    width: 100%;
    display: block;
  }
}
  }
</style>