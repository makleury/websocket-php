<?php

namespace Notifications;

use Exception;
use SplObjectStorage;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

final class NotificationsServer implements MessageComponentInterface
{
    private $clients;

    public function __construct()
    {
        $this->clients = new SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $agora = date('Y-m-d H:i:s');
        
        echo "Usuário {$conn->resourceId} conectou em: {$agora}. \n";
        
        foreach ($this->clients as $client) {
            //if ($conn !== $client) {
                $client->send('Novo Usuário na área...');
            //}
        }
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }        
        echo "Usuário {$from->resourceId} digitou: {$msg}. \n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        $agora = date('Y-m-d H:i:s');
        echo "Usuário {$conn->resourceId} desconectou em: {$agora}. \n";
    }

    public function onError(ConnectionInterface $conn, Exception $exception)
    {
        $conn->close();
    }
}
