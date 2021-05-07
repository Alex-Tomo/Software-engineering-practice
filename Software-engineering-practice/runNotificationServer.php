<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Chat;

require dirname( __FILE__ ) . '/vendor/autoload.php';

$notificationServer = IoServer::factory(
    new HttpServer(
        new WsServer(
            new \MyApp\Notification()
        )
    ),
    3002,
    '127.0.0.1'
);

$notificationServer->run();