<?php
/**
 * Created by PhpStorm.
 * User: salmanraza
 * Date: 2/8/17
 * Time: 5:52 PM
 */

namespace Tests\SudokuBundle\Library;


use PHPUnit\Framework\TestCase;
use SudokuBundle\Library\Board;
use SudokuBundle\Library\Value\NumberValue;

class BoardTest extends TestCase
{
    protected $object;
    protected $data;
    public function setUp()
    {
        $this->object = new Board(NumberValue::class, 9);
        $this->data =  [
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

    public function testMakeNewGame(){
        $this->object->makeNewGame($this->data);
        $data = $this->object->getGameAsArray();
        $this->assertEquals($data,$this->data);
    }
}