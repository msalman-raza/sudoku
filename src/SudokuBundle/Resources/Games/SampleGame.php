<?php
namespace SudokuBundle\Resources\Games;

class SampleGame{
    public static function getGame() : array
    {
        return [
            [7,null,null,null,4,null,5,3,null],
            [null,null,5,null,null,8,null,1,null],
            [null,null,8,5,null,9,null,4,null],
            [5,3,9,null,6,null,null,null,1],
            [null,null,null,null,1,null,null,null,5],
            [8,null,null,7,2,null,9,null,null],
            [9,null,7,4,null,null,null,null,null],
            [null,null,null,null,5,7,null,null,null],
            [6,null,null,null,null,null,null,5,null]
        ];
    }
}
