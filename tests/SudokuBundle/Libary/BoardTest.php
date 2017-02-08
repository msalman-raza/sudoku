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
use SudokuBundle\Library\Validator\UniqueValidator;
use SudokuBundle\Library\Value\NumberValue;

class BoardTest extends TestCase
{
    protected $object;
    protected $data;
    public function setUp()
    {
        $validator = new UniqueValidator();
        $this->object = new Board(NumberValue::class, 9 , $validator);
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

    public function testMakeNewGame()
    {
        $this->object->makeNewGame($this->data);
        $data = $this->object->getGameAsArray();
        $this->assertEquals($data,$this->data);

        return $this->object;
    }

    /**
     * @depends testMakeNewGame
     */
    public function testSetBoardCell($boardObject)
    {
        $boardObject->setCell(5 , 0, 2);
        $changedData = $this->data;
        $changedData[0][2] = 5;
        $data = $boardObject->getGameAsArray();
        $this->assertEquals($data,$changedData);
        $this->assertEquals("Error",$boardObject->getStatus());

        $boardObject->setCell(6 , 0, 2);
        $this->assertEquals("Pending",$boardObject->getStatus());

        return $this->object;
    }

    /**
     * @depends testMakeNewGame
     */
    public function testThreeSectionsCompletions($boardObject)
    {
        $boardObject->setCell(1 , 0 , 1);
        $boardObject->setCell(9 , 0 , 3);
        $boardObject->setCell(2 , 0 , 5);
        $boardObject->setCell(8 , 0 , 8);

        $this->assertEquals("Pending",$boardObject->getStatus());
        $this->assertEquals(1,$boardObject->getCompletedSectionsCount());

        $boardObject->setCell(2 , 4 , 2);
        $boardObject->setCell(1 , 5 , 2);
        $boardObject->setCell(3 , 7 , 2);
        $boardObject->setCell(4 , 8 , 2);
        $this->assertEquals("Pending",$boardObject->getStatus());
        $this->assertEquals(2,$boardObject->getCompletedSectionsCount());

        $boardObject->setCell(4 , 4 , 0);
        $boardObject->setCell(7 , 4 , 1);
        $boardObject->setCell(6 , 5 , 1);

        $this->assertEquals("Pending",$boardObject->getStatus());
        $this->assertEquals(3,$boardObject->getCompletedSectionsCount());
    }

    public function testMakeExistingGame()
    {
        $changedData = $this->data;
        $changedData[0][1] = 4;
        $this->object->makeExistingGame($this->data,$changedData);
        $data = $this->object->getGameAsArray();
        $this->assertEquals($data,$changedData);

        return $this->object;
    }

    public function testCompleteGame()
    {
        $data = [
            [3,9,1,2,8,6,5,7,4],
            [4,8,7,3,5,9,1,2,6],
            [6,5,2,7,1,4,8,3,9],
            [8,7,5,4,3,1,6,9,2],
            [2,1,3,9,6,7,4,8,5],
            [9,6,4,5,2,8,7,1,3],
            [1,4,9,6,7,3,2,5,8],
            [5,3,8,1,4,2,9,6,7],
            [null,null,6,8,9,5,3,4,1]
        ];
        $this->object->makeNewGame($data);

        $this->object->setCell(7 , 8 , 0);
        $this->assertEquals("Pending",$this->object->getStatus());

        $this->object->setCell(2 , 8 , 1);
        $data = $this->object->getCompletedSectionsCount();
        $this->assertEquals("Complete",$this->object->getStatus());
    }

    /**
     * @depends testMakeExistingGame
     */
    public function testSetBoardCellAfterCreatingExistingGame($boardObject)
    {
        $boardObject->setCell(5 , 0, 2);
        $changedData = $this->data;
        $changedData[0][1] = 4;
        $changedData[0][2] = 5;
        $data = $boardObject->getGameAsArray();
        $this->assertEquals($data,$changedData);
    }
}