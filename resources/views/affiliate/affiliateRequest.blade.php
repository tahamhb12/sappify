<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/filament/AffiliateRequest.css">
    <title>Become an affiliate</title>
</head>
<body>
    <div>
        <img src={{$affiliate_program->app->image}} alt="">
        <h1>Become an affiliate for Maestro Theme Scheduler {{ $affiliate_program->app->name }}</h1>
        <p>Sign up now to start earning {{$affiliate_program->commission_rate}}% commission for life and {{$affiliate_program->amount_per_install}}$ when your referrals install the app</p>
        <a href="{{ route('affiliate.registerPage', ['app_id' => $affiliate_program->app->id, 'unique_id' => $affiliate_program->unique_id]) }}">
            Become an affiliate
        </a>
    </div>

</body>
</html>
