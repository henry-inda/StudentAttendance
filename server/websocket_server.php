<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/config/config.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Loop;
use React\Socket\SecureServer;
use React\Socket\Server;

class WebSocketHandler implements \Ratchet\MessageComponentInterface {
    protected $clients;
    protected $userConnections = [];

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(\Ratchet\ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(\Ratchet\ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
        
        if (!$data || !isset($data['type'])) {
            return;
        }

        switch ($data['type']) {
            case 'auth':
                if (isset($data['userId'])) {
                    $this->userConnections[$data['userId']] = $from;
                    $from->userId = $data['userId'];
                    $from->userRole = $data['userRole'] ?? '';
                    echo "User {$data['userId']} authenticated\n";
                }
                break;

            case 'attendance_marked':
                $this->broadcastToUsers($data['studentIds'], [
                    'type' => 'attendance_update',
                    'scheduleId' => $data['scheduleId'],
                    'date' => $data['date'],
                    'status' => $data['status']
                ]);
                break;

            case 'schedule_change':
                $this->broadcastToRole('student', [
                    'type' => 'schedule_update',
                    'scheduleId' => $data['scheduleId'],
                    'changes' => $data['changes']
                ]);
                break;

            case 'excuse_response':
                if (isset($data['studentId'])) {
                    $this->sendToUser($data['studentId'], [
                        'type' => 'excuse_response',
                        'excuseId' => $data['excuseId'],
                        'status' => $data['status'],
                        'message' => $data['message']
                    ]);
                }
                break;
        }
    }

    public function onClose(\Ratchet\ConnectionInterface $conn) {
        $this->clients->detach($conn);
        if (isset($conn->userId)) {
            unset($this->userConnections[$conn->userId]);
        }
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(\Ratchet\ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    protected function broadcastToUsers(array $userIds, array $message) {
        foreach ($userIds as $userId) {
            $this->sendToUser($userId, $message);
        }
    }

    protected function broadcastToRole(string $role, array $message) {
        foreach ($this->clients as $client) {
            if (isset($client->userRole) && $client->userRole === $role) {
                $client->send(json_encode($message));
            }
        }
    }

    protected function sendToUser(int $userId, array $message) {
        if (isset($this->userConnections[$userId])) {
            $this->userConnections[$userId]->send(json_encode($message));
        }
    }
}

// Create event loop
$loop = Loop::get();

// Create socket server
$socket = new Server('0.0.0.0:8081', $loop);

// Set up WebSocket server
$webSocket = new IoServer(
    new HttpServer(
        new WsServer(
            new WebSocketHandler()
        )
    ),
    $socket,
    $loop
);

echo "WebSocket server running at ws://0.0.0.0:8081\n";
$webSocket->run();