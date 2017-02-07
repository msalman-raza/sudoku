<?php
/**
 * Created by PhpStorm.
 * User: salmanraza
 * Date: 2/7/17
 * Time: 5:59 PM
 */

namespace Tests\SudokuBundle\Library;


use PHPUnit\Framework\TestCase;
use SudokuBundle\Library\Cell;

class CellTest extends TestCase
{
    protected $object;

    public function setUp()
    {
        $this->object = new Cell(0,2,5,0);
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
        $this->assertEquals($this->object->getValue(),5);
    }

    public function testSetValue()
    {
        $this->object->setValue(9);
        $this->assertEquals($this->object->getValue(),9);
    }

    /**
     * @expectedException SudokuBundle\Exceptions\AccessDeniedException
     */
    public function testSetLockedValue()
    {
        $obj = new Cell(1,2,3,1);
        $obj->setValue(9);
    }
}