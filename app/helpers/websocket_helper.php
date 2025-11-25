<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use WebSocket\Client as WSClient;

class WebSocketNotifier {
    private static $instance = null;

    private function __construct() {
        // Singleton
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new WebSocketNotifier();
        }
        return self::$instance;
    }

    /**
     * Notify via WebSocket server
     * @param array $data
     * @return bool
     */
    public function notify(array $data) {
        try {
            // Textalk WebSocket client
            $client = new WSClient("ws://127.0.0.1:8080/");
            $client->send(json_encode($data));
            $client->close();
            return true;
        } catch (\Exception $e) {
            error_log('WebSocket notification failed: ' . $e->getMessage());
            return false;
        }
    }
}