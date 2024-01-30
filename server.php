<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Notifications\NotificationsServer;

    require __DIR__ . '/vendor/autoload.php';
    
    date_default_timezone_set("America/Sao_Paulo");
    
    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new NotificationsServer()
            )
        ),
        8082
    );

    $server->run();