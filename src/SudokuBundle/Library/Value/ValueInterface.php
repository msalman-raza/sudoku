<?php

namespace SudokuBundle\Library\Value;


interface ValueInterface
{
    /**
     * Get value
     *
     * @return  mixed
     *
     */
    public function get();

    /**
     * Get height of objects
     *
     * @return  int
     *
     */
    public static function getHeight() : int;
}