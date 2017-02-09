<?php

namespace SudokuBundle\Library\Value;


use SudokuBundle\Exceptions\InvalidArgumentException;

class NumberValue implements ValueInterface
{
    /**
     * @var int[]
     */
    protected static $possibleValues = [1,2,3,4,5,6,7,8,9];

    /**
     * @var int
     */
    protected $value;

    /**
     * Creates cell object
     *
     * @param int $value value of object
     *
     * @return NumberValue
     */
    public function __construct(int $value)
    {
        if($this->validate($value)){
            $this->value = $value;
        }
    }

    /**
     * Get value
     *
     * @return  int
     *
     */
    public function get() : int
    {
        return $this->value;
    }

    /**
     * Get height of objects
     *
     * @return  int
     *
     */
    public static function getHeight() : int
    {
        return count(Self::$possibleValues);
    }

    /**
     * Validate value is in possible values
     *
     * @return  bool
     *
     * @throws \TypeError if value is not in possible values
     *
     */
    protected function validate(int $value) : bool
    {
        if (!in_array($value,Self::$possibleValues)){
            throw new \TypeError("Out of bound value.");
        }
        return true;
    }
}