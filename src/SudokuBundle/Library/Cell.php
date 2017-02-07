<?php namespace SudokuBundle\Library;


use SudokuBundle\Exceptions\AccessDeniedException;
use SudokuBundle\Exceptions\InvalidArgument;

class Cell
{
    protected $x;
    protected $y;
    protected $value;
    protected $lock;
    protected $valid = true;

    public function __construct(
        int $x,
        int $y,
        $value,
        bool $lock
    )
    {
        $this->x = $x;
        $this->y = $y;
        $this->value = $value;
        $this->lock = $lock;
    }

    public function getX() : int
    {
        return $this->x;
    }

    public function getY() : int
    {
        return $this->y;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        if(!$this->lock){
            $this->value = $value;
        } else {
            throw new AccessDeniedException();
        }

    }

}