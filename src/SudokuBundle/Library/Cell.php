<?php

namespace SudokuBundle\Library;


use SudokuBundle\Exceptions\AccessDeniedException;
use SudokuBundle\Exceptions\InvalidArgument;
use SudokuBundle\Library\Value\ValueInterface;

class Cell
{
    protected $x;
    protected $y;
    protected $value;
    protected $lock;
    protected $valid = true;
    protected $validator;


    public function __construct(
        int $x,
        int $y,
        ValueInterface $value = null,
        bool $lock = false
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
        return ($this->value) ? $this->value->get() : null;
    }

    public function setValue(ValueInterface $value)
    {
        if(!$this->lock){
            $this->value = $value;
        } else {
            throw new AccessDeniedException();
        }

    }

    public function isLocked() : bool
    {
       return $this->lock;

    }

}