<?php

namespace Tests\SudokuBundle\Library\Validator;


use PHPUnit\Framework\TestCase;
use SudokuBundle\Library\Cell;
use SudokuBundle\Library\Validator\UniqueValidator;
use SudokuBundle\Library\Value\NumberValue;

class UniqueValidatorTest extends TestCase
{
    protected $sections = [];
    protected $valueObjects = [];
    protected $object;

    public function setUp(){
        for($i = 1 ; $i < 10 ; $i++){
            $this->valueObjects[] = new NumberValue($i);
        }
        $this->object = new UniqueValidator();
    }

    public function testHalfEmptyValidCells()
    {
        $this->sections[0] = new Cell(0 , 0 , $this->valueObjects[0]);
        $this->sections[1] = new Cell(0 , 1 );
        $this->sections[2] = new Cell(0 , 2 , $this->valueObjects[2]);
        $this->sections[3] = new Cell(0 , 3 );
        $this->sections[4] = new Cell(0 , 4 , $this->valueObjects[4]);
        $this->sections[5] = new Cell(0 , 5 );
        $this->sections[6] = new Cell(0 , 6 , $this->valueObjects[6]);
        $this->sections[7] = new Cell(0 , 7 );
        $this->sections[8] = new Cell(0 , 8 , $this->valueObjects[8]);
        $this->object->validate($this->sections);
        $this->assertEquals("Pending",$this->object->getStatus());

        return $this->sections;
    }


    /**
     * @depends testHalfEmptyValidCells
     */
    public function testHalfEmptyRepeatedCells($sections)
    {
        $sections[1] = new Cell(0 , 2 , $this->valueObjects[2]);
        $sections[5] = new Cell(0 , 2 , $this->valueObjects[2]);
        $this->object->validate($sections);
        $this->assertEquals("Error",$this->object->getStatus());


    }

    /**
     * @depends testHalfEmptyValidCells
     */
    public function testCompleteInValidCells($sections)
    {
        $sections[1] = new Cell(0 , 1 , $this->valueObjects[2]);
        $sections[3] = new Cell(0 , 3 , $this->valueObjects[3]);
        $sections[5] = new Cell(0 , 5 , $this->valueObjects[2]);
        $sections[7] = new Cell(0 , 7 , $this->valueObjects[7]);
        $this->object->validate($sections);
        $this->assertEquals("Error",$this->object->getStatus());
        $conflicts = $this->object->getConflicts();
        $this->assertCount(3,$conflicts);
        $this->assertEquals(0,$conflicts[5]['x']);
        $this->assertEquals(5,$conflicts[5]['y']);
    }

    /**
     * @depends testHalfEmptyValidCells
     */
    public function testCompleteValidCells($sections)
    {
        $sections[1] = new Cell(0 , 1 , $this->valueObjects[1]);
        $sections[3] = new Cell(0 , 3 , $this->valueObjects[3]);
        $sections[5] = new Cell(0 , 5 , $this->valueObjects[5]);
        $sections[7] = new Cell(0 , 7 , $this->valueObjects[7]);
        $this->object->validate($sections);
        $this->assertEquals("Complete",$this->object->getStatus());


    }
}