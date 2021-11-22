<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Promo</title>
    <style>
        body {
            background: #2c3e50;
            color: white;
            margin: 0;
            padding: 0;
            height: 100%;
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
			margin-top: 70px;
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
						<span id="countdown">{{ $campaign->timer }}</span>
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
                    window.location.href = response.last_page;
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
