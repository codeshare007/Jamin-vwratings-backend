<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Promo</title>

    <script type="text/javascript" src="/assets/js/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/assets/css/styles.css">
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
    $(function () {
        var timeleft = {{ $campaign->timer }};
        var downloadTimer = setInterval(function () {
            if (timeleft <= 0) {
                clearInterval(downloadTimer);
                $.post("/api/v1/end-promo", { csrf: "{{ @csrf_token() }}" }).done(function (data) {
                    window.location.href = data.last_page;
                });

            } else {
                document.getElementById("countdown").innerHTML = timeleft;
            }
            timeleft -= 1;
        }, 1000);
    })
</script>
</body>
</html>
