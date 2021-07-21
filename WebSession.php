<?php
declare(strict_types=1);

namespace libPrometheus;

use Exception;
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
    private Socket $socket;

    public function __construct(Socket $socket)
    {
        $this->socket = $socket;
    }


    /**
     * @return Socket
     */
    public function getSocket(): Socket
    {
        return $this->socket;
    }

    /**
     * @return string
     * Read a string from the request socket
     * @throws Exception
     */
    public function read(): string
    {
        $result = socket_read($this->socket, 1024);
        if (!$result) {
            throw new Exception("Cannot read from socket");
        }

        return $result;
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
    public function sendData(string $type, string $payload): void
    {
        $this->write("HTTP/1.1 200 OK\r\nContent-Type: " . $type . "\r\n\r\n" . $payload);
    }
}