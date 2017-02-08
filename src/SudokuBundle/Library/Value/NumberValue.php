<?php

namespace SudokuBundle\Library\Value;


use SudokuBundle\Exceptions\InvalidArgumentException;

class NumberValue implements ValueInterface
{
    protected static $possibleValues = [1,2,3,4,5,6,7,8,9];
    protected $value;


    public function __construct(int $value)
    {
        if($this->validate($value)){
            $this->value = $value;
        }
    }

    public function get() : int
    {
        return $this->value;
    }

    public static function getHeight() : int
    {
        return count(Self::$possibleValues);
    }

    protected function validate(int $value) : bool
    {
        if (!in_array($value,Self::$possibleValues)){
            throw new \TypeError("Out of bound value.");
        }
        return true;
    }
}