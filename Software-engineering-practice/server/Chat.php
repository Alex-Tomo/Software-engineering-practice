<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Exception;
require dirname(__dir__) . '/db_connector.php';

class Chat implements MessageComponentInterface {

    protected $clients;
    protected $users;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->users = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        echo "User Connected: ({$conn->resourceId})\n";
        $this->clients->attach($conn);
        $this->users[$conn->resourceId] = $conn;
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $databaseConnection = getConnection();

        $data = json_decode($msg, true);
        // $data['email']; <- use to get the users id
        // $data['jobId']; <- the user being messaged
        // $data['msg']; <- the message itself
        // $data['otherUserId']; <- the message itself

        $result = $databaseConnection->query("
            SELECT user_fname, user_lname, sep_user_info.user_id FROM sep_user_info
            JOIN sep_users ON sep_user_info.user_id = sep_users.user_id
            WHERE user_email = '{$data['email']}' 
        ");
        $name = $result->fetchObject();

        $d['userId'] = $name->user_id; // emma
        $d['otherUserId'] = $data['otherUserId'];
        $d['jobId'] = $data['jobId'];
        $d['msg'] = $data['msg']; // alex
        $d['datetime'] = date('Y-m-d H:i:s');

        foreach($this->clients as $client) {
            if($from == $client) {
                $d['from'] = 'You';
            } else {
                $d['from'] = "{$name->user_fname}";
            }
            $client->send(json_encode($d));
        }
    }

    public function onClose(ConnectionInterface $conn) {
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}