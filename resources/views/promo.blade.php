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

        h1 {
            font-family: 'Helvetica', sans-serif;
            font-weight: normal;
            margin-bottom: 30px;
        }

        .promoPage {
            min-height: inherit;
        }

        .promoPage__header {
            padding: 10px;
        }

        .promoPage__content {
            display: block;
            padding: 20px;
            height: calc(100% - 118px);
            min-height: inherit;
        }
    </style>
</head>

<body>
<div class="promoPage">
    <div class="promoPage__header">
        <h1>Continue stalking in <span id="countdown">{{ $campaign->timer }}</span></h1>

        <hr>
    </div>
    <div class="promoPage__content">
        {!! $campaign->content !!}
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
