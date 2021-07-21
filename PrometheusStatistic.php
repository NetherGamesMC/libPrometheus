<?php
declare(strict_types=1);

namespace libPrometheus;

use JetBrains\PhpStorm\Pure;
use Threaded;

class PrometheusStatistic extends Threaded
{

    public const TYPE_COUNTER = "counter";
    public const TYPE_GAUGE = "gauge";

    private int $value;
    private string $identifier;
    private string $help;
    private string $type;

    public function __construct(int $value, string $identifier, string $help, string $type)
    {
        $this->value = $value;
        $this->identifier = $identifier;
        $this->help = $help;
        $this->type = $type;
    }

    public function toReturnableWebString(): string
    {
        return "# HELP " . $this->identifier . " " . $this->getHelp() . PHP_EOL
            . "# TYPE " . $this->identifier . " " . $this->type . PHP_EOL
            . $this->identifier . " " . $this->value;
    }

    /**
     * @return string
     */
    public function getHelp(): string
    {
        return $this->help;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param string $help
     */
    public function setHelp(string $help): void
    {
        $this->help = $help;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param int $value
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }
}