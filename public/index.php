<?php
// ローカル用index.php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());


/*  Xサーバー用のindex.php
<?php

// Xserver のドキュメントルート public_html から
// Laravel の public ディレクトリへ流す

require __DIR__ . '/diy_lab_app/vendor/autoload.php';
$app = require_once __DIR__ . '/diy_lab_app/bootstrap/app.php';

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
*/