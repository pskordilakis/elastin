<?php

namespace Elastin;

use ArrayAccess;
use JsonSerializable;
use stdClass;

/**
 * Implements basic query object
 * It can be accessed as array with dot notation parameter
 */
class Query implements ArrayAccess, JsonSerializable
{
    /**
     * @var q
     */
    private $q;

    public function __construct()
    {
        $this->q = [];
    }

    /**
     * Get data as array
     *
     * @param void
     *
     * @return array
     */
    public function all(): array
    {
        return $this->q;
    }

    public function empty(): bool
    {
        return empty($this->q);
    }

    /**
     * Whether or not an offset exists
     *
     * @param string An offset to check for
     * @access public
     *
     * @return boolean
     * @abstracting ArrayAccess
     */
    public function offsetExists($offset): bool
    {
        $fields = explode('.', $offset);

        $val = $this->q;

        foreach ($fields as $field) {
            if (is_array($val) && array_key_exists($field, $val)) {
                $val = $val[$field];
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns the value at specified offset
     *
     * @param string The offset to retrieve
     * @access public
     *
     * @return mixed
     * @abstracting ArrayAccess
     */
    public function offsetGet($offset)
    {
        $fields = explode('.', $offset);

        $pivot = &$this->q;

        foreach ($fields as $field) {
            if (array_key_exists($field, $pivot)) {
                $pivot = &$pivot[$field];
            } else {
                return null;
            }
        }

        return $pivot;
    }

    /**
     * Assigns a value to the specified offset
     *
     * @param string The offset to assign the value to
     * @param mixed  The value to set
     *
     * @access public
     * @abstracting ArrayAccess
     */
    public function offsetSet($offset, $value): void
    {
        $fields = explode('.', $offset);

        $pivot = &$this->q;

        foreach ($fields as $field) {
            $pivot = &$pivot[$field];
        }

        $pivot = $value;
    }

    /**
     * Unsets an offset
     *
     * @param string The offset to unset
     *
     * @access public
     * @abstracting ArrayAccess
     */
    public function offsetUnset($offset): void
    {
        $fields = explode('.', $offset);
        $field = array_pop($fields);

        $pivot = &$this->q;

        foreach ($fields as $part) {
            $pivot = &$pivot[$part];
        }

        unset($pivot[$field]);
    }

    /**
     * Provide data that will be serialized
     * with json_encode
     *
     * @param void
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->all();
    }
}
