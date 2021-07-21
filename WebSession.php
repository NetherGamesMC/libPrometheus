<?php
declare(strict_types=1);

namespace libPrometheus;

use Socket;

/**
 * Class WebSession
 * @package NetherGames\NGEssentials\exporter
 * Every WebSession describes and controls one client opening the web page
 */
class WebSession
{
    /**
     * @var Socket
     */
    private $socket;

    public function __construct($socket)
    {
        $this->socket = $socket;
    }


    /**
     * @return resource
     */
    public function getSocket(): mixed
    {
        return $this->socket;
    }

    /**
     * @return string
     * Read a string from the request socket
     */
    public function read(): string
    {
        return socket_read($this->socket, 1024);
    }

    /**
     * @param string $payload
     * Write to the client requesting the data
     */
    public function write(string $payload): void
    {
        socket_write($this->socket, $payload);
    }

    /**
     * Close the client socket
     */
    public function close(): void
    {
        socket_close($this->socket);
    }

    /**
     * @param string $type
     * @param string $payload
     * Send data in a HTTP/1.1 format
     */
    public function sendData(string $type, string $payload)
    {
        $this->write("HTTP/1.1 200 OK\r\nContent-Type: " . $type . "\r\n\r\n" . $payload);
    }
}