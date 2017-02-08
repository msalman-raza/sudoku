<?php
/**
 * Created by PhpStorm.
 * User: salmanraza
 * Date: 2/7/17
 * Time: 3:06 PM
 */

namespace SudokuBundle\Root;



class Game
{
    public function __construct()
    {

    }

    public function createGame(){


        return "Game Created";
    }
}

class Section{
    private $cell;
    public function __construct($num)
    {
        $this->cell = $num;
    }

    public function setCell($num)
    {
        $this->cell = $num;
    }

    public function getCellNumber()
    {
        return $this->cell->getNumber();
    }
}

class Cell{
    private $number;

    public function __construct($num)
    {
        $this->number = $num;
    }

    public function setNumber($num)
    {
        $this->number = $num;
    }

    public function getNumber()
    {
       return $this->number;
    }
}