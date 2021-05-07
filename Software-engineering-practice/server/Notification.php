<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Exception;
require dirname(__dir__) . '/db_connector.php';

class Notification implements MessageComponentInterface {

    protected $clients;
    protected $users;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->users = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        $databaseConnection = getConnection();

        echo "User Connected (Notification): ({$conn->resourceId})\n";
        $this->clients->attach($conn);
        $this->users[$conn->resourceId] = $conn;
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        $data['name'];
        $data['jobTitle'];
        $data['desc'];
        $data['userId'];
        $data['jobId'];
        $data['dt'];

        echo $data['userId'] . " " . $data['jobId'];

        foreach($this->clients as $client) {
//            if($from != $client) {
                $client->send(json_encode($data));
//            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}