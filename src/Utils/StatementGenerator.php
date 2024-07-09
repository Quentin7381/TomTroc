<?php

namespace Utils;

use \PDOStatement;

class StatementGenerator extends Generator
{
    protected PDOStatement $stmt;
    protected $fetch;
    protected int $position = 0;

    public function __construct(PDOStatement $stmt)
    {
        $this->stmt = $stmt;
        $this->stmt->execute();
        $this->next();
    }

    public function current() : mixed
    {
        $fetch = $this->fetch;

        if ($fetch === false) {
            return false;
        }

        if (isset($this->callbacks['current_post_process'])) {
            $fetch = ($this->callbacks['current_post_process'])($fetch);
        }

        return $fetch;
    }

    public function valid() : bool
    {
        return $this->fetch !== false;
    }

    public function next() : void
    {
        $this->fetch = $this->stmt->fetch(\PDO::FETCH_ASSOC);
        $this->position++;
    }

    public function rewind() : void
    {
        $this->stmt->execute();
        $this->fetch = $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function key() : int
    {
        return $this->position;
    }
}
