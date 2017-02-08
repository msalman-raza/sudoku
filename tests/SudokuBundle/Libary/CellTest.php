<?php

namespace Tests\SudokuBundle\Library;


use PHPUnit\Framework\TestCase;
use SudokuBundle\Library\Cell;
use SudokuBundle\Library\Validator\GenericCellValidator;
use SudokuBundle\Library\Value\NumberValue;

class CellTest extends TestCase
{
    protected $object;
    protected $validator;


    public function setUp()
    {
        $valueObject = new NumberValue(3);
        $this->object = new Cell(0,2,$valueObject);
    }

    public function tearDown()
    {
        $this->object = null;
    }

    public function testGetX()
    {
        $this->assertEquals($this->object->getX(),0);
    }

    public function testGetY()
    {
        $this->assertEquals($this->object->getY(),2);
    }

    public function testGetValue()
    {
        $this->assertEquals($this->object->getValue(),3);
    }

    public function testSetValue()
    {
        $valueObject = new NumberValue(9);
        $this->object->setValue($valueObject);
        $this->assertEquals($this->object->getValue(),9);
    }

    public function testNullValue()
    {
        $obj = new Cell(1,2);
        $this->assertNull($obj->getValue());
    }

    public function testIsLocked()
    {
        $this->assertFalse($this->object->isLocked());
        $valueObject = new NumberValue(9);
        $obj = new Cell(1,2,$valueObject,1);
        $this->assertTrue($obj->isLocked());
    }

    /**
     * @expectedException SudokuBundle\Exceptions\AccessDeniedException
     */
    public function testSetLockedValue()
    {
        $valueObject = new NumberValue(6);
        $obj = new Cell(1,2,$valueObject,1);
        $obj->setValue($valueObject);
    }


}