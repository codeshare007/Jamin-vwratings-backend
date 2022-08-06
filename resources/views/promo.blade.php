<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Promo</title>
	
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-TV2N64S6FN"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'G-TV2N64S6FN');
	</script>
	
    <style>
        body {
            background: #2c3e50;
            color: white;
            margin: 0;
            padding: 0;
        }

        .promoPage {
            min-height: inherit;
        }

        .promoPage__content {
            display: block;
            padding: 20px;
            height: calc(100% - 118px);
            min-height: inherit;
        }
		
		.promowords {
			font-family: monospace;
			margin-top: 20px;
			width: fit-content;
			border: 1px solid black;
			margin-left: auto;
			margin-right: auto;
			padding-left: 10px;
			padding-right: 10px;
		}
    </style>
</head>

<body>
<div class="promoPage">

   <div class="page-container">      
      <div class="container-fluid tm-content-container">
            <div class="row">
              <div class="col-md-12">
					<div align="center" class="promowords">
						<h2>Take a break and enjoy this ad while the system does it's thing!</h2>
					</div>
                     <div align="center" class="promoPage__content" >
						{!! $campaign->content !!}
					<br><br>
						<span id="countdown">{{ $campaign->timer }}</span> seconds left
					</div>
              </div>             
            </div>
      </div>
</div>
</div>
<script>    
    var timeleft = {{ $campaign->timer }};
        
    var downloadTimer = setInterval(function () {
        if (timeleft <= 0) {
            clearInterval(downloadTimer);
            var http = new XMLHttpRequest();
            var params = new FormData();
            params.append('csrf', "{{ @csrf_token() }}");
            http.open('POST', '/api/v1/end-promo', true);

            http.onreadystatechange = function () {//Call a function when the state changes.
                if (http.readyState == 4 && http.status == 200) {
                    var response = JSON.parse(http.response);
                    if ({{$type}} == 1) {
						window.location.href = response.last_page;
                    } else if ({{$type}} == 2) {
                        window.location.href = response.last_page;
                    } else if ({{$type}} == 3) {
                        window.location.href = response.last_page;
					} else if ({{$type}} == 4) {
                        window.location.href = '/creeps';
					} else if ({{$type}} == 7) {
                        window.location.href = '/creeptimer';
                    }
                }
            }

            http.send(params);

        } else {
            document.getElementById("countdown").innerHTML = timeleft;
        }
        timeleft -= 1;
    }, 1000);
</script>
</body>
</html>
