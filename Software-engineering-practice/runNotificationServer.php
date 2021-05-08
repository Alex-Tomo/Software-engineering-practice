<?php

    // Uses Ratchet websockets library

    // Notification server, run before starting the website
    // uses websockets to automatically update the users notification icon
    // and display the notifications to the user

    // Runs on port 3002 on localhost

    use MyApp\Notification;
    use Ratchet\Server\IoServer;
    use Ratchet\Http\HttpServer;
    use Ratchet\WebSocket\WsServer;

    require dirname( __FILE__ ) . '/vendor/autoload.php';

    $notificationServer = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Notification()
            )
        ),
        3002,
        '127.0.0.1'
    );

    $notificationServer->run();

?>