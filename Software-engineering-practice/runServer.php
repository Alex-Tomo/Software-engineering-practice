<?php

    // Uses Ratchet websockets library

    // Chat server, run before starting the website
    // uses websockets to automatically update the users chat messages
    // and display the messages to the relevant users

    // Runs on port 3001 on localhost

    use Ratchet\Server\IoServer;
    use Ratchet\Http\HttpServer;
    use Ratchet\WebSocket\WsServer;
    use MyApp\Chat;

    require dirname( __FILE__ ) . '/vendor/autoload.php';

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Chat()
            )
        ),
        3001,
        '127.0.0.1'
    );

    $server->run();

?>