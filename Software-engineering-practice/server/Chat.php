<?php

    // Chat websocket used for the messaging section
    // gives the user an id on onopen
    // sends the data back to the relevant message users onmessage
    // Server starts in (sendMessages.js)

    namespace MyApp;
    use Ratchet\MessageComponentInterface;
    use Ratchet\ConnectionInterface;

    require dirname(__dir__) . '/db_connector.php';

class Chat implements MessageComponentInterface {

    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        echo "User Connected: ({$conn->resourceId})\n";
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        // $data['email']; <- use to get the users id

        $databaseConnection = getConnection();

        $getUserInfo = $databaseConnection->query("
            SELECT user_fname, user_lname, sep_user_info.user_id FROM sep_user_info
            JOIN sep_users ON sep_user_info.user_id = sep_users.user_id
            WHERE user_email = '{$data['email']}' 
        ");
        $userInfo = $getUserInfo->fetchObject();

        $d['userId'] = $userInfo->user_id;          // -> the users id
        $d['otherUserId'] = $data['otherUserId'];   // -> the user being messaged id
        $d['jobId'] = $data['jobId'];               // -> the job being talked about
        $d['msg'] = $data['msg'];                   // -> the message being send
        $d['datetime'] = date('Y-m-d H:i:s');// -> the current datetime

        // check whom sent the message and add the corresponding
        // name (You or first name)
        // Data gets sent to (sendMessages.js)
        foreach ($this->clients as $client) {
            if ($from == $client) {
                $d['from'] = 'You';
            } else {
                $d['from'] = "{$userInfo->user_fname}";
            }
            $client->send(json_encode($d));
        }
    }

    public function onClose(ConnectionInterface $conn) {
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}