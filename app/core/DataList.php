<?php

class DataList implements ArrayAccess, Iterator, JsonSerializable
{
    /**
     * @var Array
     */
    protected $source;

    /*
     * @var Int
     */
    protected $count;

    /**
     * @var Int
     */
    protected $totalSize;

    /**
     * @var Int
     */
    protected $position;

    public function __construct($totalSize, $source = null)
    {
        $this->source = $source;
        $this->position = 0;
        $this->totalSize = $totalSize;
    }

    public function count()
    {
        return count($this->source);
    }

    public function getTotalSize() {
        return $this->totalSize;
    }

    public function offsetExists($offset)
    {
        return isset($this->source[$offset]);
    }


    public function offsetGet($offset)
    {
        return isset($this->source[$offset]) ? $this->source[$offset] : null;
    }


    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->source[] = $value;
        } else {
            $this->source[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->source[$offset]);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->source[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->source[$this->position]);
    }

    public function jsonSerialize() {
        return array(
            'totalSize' => $this->getTotalSize(),
            'items' => $this->source,
        );
    }
}
