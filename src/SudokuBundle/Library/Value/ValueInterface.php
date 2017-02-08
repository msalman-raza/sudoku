<?php

namespace SudokuBundle\Library\Value;


interface ValueInterface
{
    public function get();
    public static function getHeight() : int;
}