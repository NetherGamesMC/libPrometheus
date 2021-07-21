<?php
declare(strict_types=1);

namespace libPrometheus;

use Exception;
use pocketmine\thread\Thread;
use Socket;

class PrometheusExportingServer extends Thread
{
    /** @var array<string, PrometheusStatistic> */
    private array $datasets = [];

    /** @var Socket */
    private Socket $socket;

    /**
     * @throws Exception
     */
    public function __construct(int $port)
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if(!$this->socket) throw new Exception("Cannot create socket.");
        if (!socket_bind($this->socket, "0.0.0.0", $port)) {
            throw new Exception("Failed to bind to 0.0.0.0:" . $port);
        }
        socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_listen($this->socket);
    }

    protected function onRun(): void
    {
        $this->registerClassLoader();
        while ($this->isRunning()) {
            $client = socket_accept($this->socket);
            if ($client instanceof Socket) {
                $session = new WebSession($client);
                $buffer = "";
                foreach (((array) $this->datasets) as $dataset) {
                    $buffer .= $dataset->toReturnableWebString();
                }
                $session->read();
                $session->sendData("text/plain", $buffer);
                $session->close();
            }
        }
    }

    /**
     * @param PrometheusStatistic $statistic
     * Add a statistic to the map, or overwrite if existant
     */
    public function addStatistic(PrometheusStatistic $statistic): void
    {
        $this->datasets[$statistic->getIdentifier()] = $statistic;
    }

    /**
     * @param string $identifier
     * @return PrometheusStatistic|null
     * Get a statistic object
     */
    public function getStatistic(string $identifier): ?PrometheusStatistic
    {
        return array_key_exists($identifier, (array)$this->datasets) ? ((array)$this->datasets)[$identifier] : null;
    }
}