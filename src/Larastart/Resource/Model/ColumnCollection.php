<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Resource\Model;

class ColumnCollection implements \Iterator
{
    protected $columns = [];
    protected $originalFile;
    protected $index;

    public function __construct(array $values)
    {
        $this->rewind();
        $this->parseValues($values);
    }

    protected function parseValues(array $values)
    {
        foreach ($values as $i => $col) {
            $this->columns[$i] = new Column($col);
        }
    }

    public function current()
    {
        return $this->columns[$this->index];
    }

    public function next()
    {
        $this->index++;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return isset($this->columns[$this->index]);
    }

    public function rewind()
    {
        $this->index = 0;
    }
}
