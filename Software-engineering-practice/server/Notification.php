<?php

    // Notification web socket
    // Stores the users id on onopen
    // Gets the data on message and sends it back to all relevant users

    namespace MyApp;
    use Ratchet\MessageComponentInterface;
    use Ratchet\ConnectionInterface;

    require dirname(__dir__) . '/db_connector.php';

class Notification implements MessageComponentInterface {

    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;}

    public function onOpen(ConnectionInterface $conn) {

        echo "User Connected (Notification): ({$conn->resourceId})\n";
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // gets the data from enquireForm.js

        $data = json_decode($msg, true);

        // Data recieved from $msg
        // $data['name'];       --> name of the person sending the notification
        // $data['jobTitle'];   --> name of the job title being enquired about
        // $data['desc'];       --> body of the message of the notification
        // $data['userId'];     --> id of the user whom owns the job
        // $data['jobId'];      --> id of the job being enquired
        // $data['dt'];         --> current datetime

        // sends the data back (notificationServer.js)
        foreach($this->clients as $client) {
            $client->send(json_encode($data));
        }
    }

    public function onClose(ConnectionInterface $conn) {
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}