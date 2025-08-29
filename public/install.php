<?php


$site_url = 'http://www.my-site-url.com';

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';


$laravel_dir = __DIR__ . '/path/to/laravel';

require $laravel_dir . '/bootstrap/autoload.php';

$app = require_once $laravel_dir . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo 'Installing...<br>';
$kernel->call('migrate', ['--force' => true]);

echo 'Seeding...<br>';
$kernel->call('db:seed', ['--force' => true]);

// redirect
echo "<script>window.location = '$site_url'</script>";