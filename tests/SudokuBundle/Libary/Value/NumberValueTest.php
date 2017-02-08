<?php

namespace Tests\SudokuBundle\Library\Value;


use PHPUnit\Framework\TestCase;
use SudokuBundle\Library\Value\NumberValue;

class NumberValueTest extends TestCase
{
    public function testNumberValueMake()
    {
        $object = new NumberValue(6);
        $this->assertEquals(6,$object->get());
    }

    /**
     * @expectedException \TypeError
     */
    public function testOutOfBoundValue()
    {
        $object = new NumberValue(15);
    }

    /**
     * @expectedException \TypeError
     */
    public function testStringValue()
    {
        $object = new NumberValue("a");
    }

}