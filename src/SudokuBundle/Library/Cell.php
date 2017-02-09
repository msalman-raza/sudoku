<?php

namespace SudokuBundle\Library;


use SudokuBundle\Exceptions\AccessDeniedException;
use SudokuBundle\Exceptions\InvalidArgument;
use SudokuBundle\Library\Value\ValueInterface;

class Cell
{
    /**
     * @var int
     */
    protected $x;

    /**
     * @var int
     */
    protected $y;

    /**
     * @var ValueInterface
     */
    protected $value;

    /**
     * @var bool
     */
    protected $lock;


    /**
     * Creates cell object
     *
     * @param int $x row #
     * @param int $y column #
     * @param ValueInterface $value value Object
     * @param bool $lock cell is locked for changes
     *
     * @return Cell
     */
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

    /**
     * Get row of cell
     *
     * @return int
     */
    public function getX() : int
    {
        return $this->x;
    }

    /**
     * Get column of cell
     *
     * @return int
     */
    public function getY() : int
    {
        return $this->y;
    }

    /**
     * Get column of cell
     *
     * @return mixed value of cell
     */
    public function getValue()
    {
        return ($this->value) ? $this->value->get() : null;
    }

    /**
     * Creates cell object
     *
     * @param ValueInterface $value value Object
     *
     * @throws AccessDeniedException if cell is locked
     */
    public function setValue(ValueInterface $value)
    {
        if(!$this->lock){
            $this->value = $value;
        } else {
            throw new AccessDeniedException();
        }

    }

    /**
     * Get locked flag of cell
     *
     * @return bool
     */
    public function isLocked() : bool
    {
       return $this->lock;

    }

}