<?php
namespace SudokuBundle\Library;


use SudokuBundle\Exceptions\InvalidArgumentException;
use SudokuBundle\Library\Value\ValueInterface;
use SudokuBundle\Repository\SudokuGameRepository;

class Board
{
    protected $id;
    protected $hash;
    protected $cells;
    protected $valueClass;
    protected $dimension;
    protected $sections;

    public function __construct( string $valueClass, int $dimension)
    {
        $this->setValueClass($valueClass);
        $this->setDimension($dimension);
    }


    public function makeNewGame(array $data)
    {
        $this->makeHash();
        for ($x=0 ; $x<$this->dimension ; $x++){
            for ($y=0 ; $y<$this->dimension ; $y++){
                $value = $this->makeValueObject($data[$x][$y]);
                $cell = new Cell($x,$y,$value,1);
                $this->addCell($cell);
            }
        }
    }

    public function getGameAsArray() : array
    {
        $gameArray = [];
        for ($x=0 ; $x<$this->dimension ; $x++){
            for ($y=0 ; $y<$this->dimension ; $y++){
                $gameArray[$x][$y] = $this->cells[$x][$y]->getValue();
            }
        }
        return $gameArray;
    }

    public function getLockedGameAsArray() : array
    {
        $gameArray = [];
        for ($x=0 ; $x<$this->dimension ; $x++){
            for ($y=0 ; $y<$this->dimension ; $y++){
                $cell = $this->cells[$x][$y];
                $gameArray[$x][$y] = ($cell->isLocked()) ? $this->cells[$x][$y]->getValue() : null;
            }
        }
        return $gameArray;
    }

    protected function setValueClass(string $valueClass)
    {
        if(is_a($valueClass,'SudokuBundle\Library\Value\ValueInterface',true)){
            $this->valueClass = $valueClass;
        }else{
            throw new \TypeError("Invalid class");
        }
    }

    protected function setDimension(int $dimension)
    {
        $class = $this->valueClass;
        if($class::getHeight() == $dimension && $this->isPerfectSquare($dimension)){
            $this->dimension = $dimension;
        }else{
            throw new InvalidArgumentException("Invalid dimension.");
        }
    }

    protected function isPerfectSquare(int $dimension)
    {
        return sqrt($dimension) == floor(sqrt($dimension));
    }
    protected function makeValueObject($primaryValue , $secondaryValue = null)
    {
        $primaryValue = ($primaryValue != "") ? $primaryValue : null;
        $secondaryValue = ($secondaryValue != "") ? $secondaryValue : null;
        $data = $primaryValue ?? $secondaryValue;
        if($data){
            return new $this->valueClass($data);
        }
        return null;
    }

    protected function addCell(Cell $cell)
    {
        $x = $cell->getX();
        $y = $cell->getY();
        $this->cells[$x][$y] = $cell;
        $cellSections = $this->getCellSections($cell);
        $this->sections[$cellSections[0]][] = $cell;
        $this->sections[$cellSections[1]][] = $cell;
        $this->sections[$cellSections[2]][] = $cell;
    }

    protected function getCellSections(Cell $cell){
        $cellSections[] = $cell->getX();
        $cellSections[] = $this->dimension + $cell->getY();
        $cellSections[] = ($this->dimension * 2) + $this->getRegion($cell);
        return $cellSections;
    }

    protected function getRegion(Cell $cell){
        $length = sqrt($this->dimension);
        $regionRow = floor($cell->getX() / $length);
        $regionCol = floor($cell->getY() / $length);
        return ($regionRow * $length) + $regionCol;
    }

    protected function makeHash()
    {
        $this->hash = substr( md5(rand()), 0, 15);
    }

}