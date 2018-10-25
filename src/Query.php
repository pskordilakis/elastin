<?php

namespace Elastin;

use JsonSerializable;

/**
 * Implements basic query object
 * It can be accessed as array with dot notation parameter
 */
class Query implements JsonSerializable
{
    /**
     * @var \Elastin\Container
     */
    public $headers;

    /**
     * @var \Elastin\Container
     */
    public $body;

    public function __construct()
    {
        $this->headers = new Container();
        $this->body = new Container();
    }

    /**
     * Get data as array
     *
     * @return array
     */
    public function all(): array
    {
        return [
            'headers' => $this->headers->all(),
            'body' => $this->body->all()
        ];
    }

    /**
     * Returns if the query is empty
     *
     * @return bool
     */
    public function empty(): bool
    {
        return $this->headers->empty() && $this->body->empty();
    }

    /**
     * Provide data that will be serialized
     * with json_encode
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->all();
    }
}
