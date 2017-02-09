<?php
namespace SudokuBundle\Library;


use SudokuBundle\Exceptions\InvalidArgumentException;
use SudokuBundle\Library\Validator\ValidatorInterface;
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
    protected $completedSections = [];
    protected $conflicts;
    protected $validator;


    public function __construct(
        string $valueClass,
        int $dimension,
        ValidatorInterface $validator
    )
    {
        $this->setValueClass($valueClass);
        $this->setDimension($dimension);
        $this->validator = $validator;
    }


    public function makeNewGame(array $data)
    {
        $this->makeHash();
        for ($x=0 ; $x<$this->dimension ; $x++){
            for ($y=0 ; $y<$this->dimension ; $y++){
                $value = $this->makeValueObject($data[$x][$y]);
                $lock = ($data[$x][$y] == "")?false:true;
                $cell = new Cell($x,$y,$value,$lock);
                $this->addCell($cell);
            }
        }
        $this->validate();
    }

    public function makeExistingGame(array $lockedData, array $data)
    {
        $this->makeHash();
        for ($x=0 ; $x<$this->dimension ; $x++){
            for ($y=0 ; $y<$this->dimension ; $y++){
                $value = $this->makeValueObject($lockedData[$x][$y],$data[$x][$y]);
                $lock = ($lockedData[$x][$y] == "")?false:true;
                $cell = new Cell($x,$y,$value,$lock);
                $this->addCell($cell);
            }
        }
        $this->validate();
    }

    public function setCell($value , int $x , int $y)
    {
        $valueObject = new $this->valueClass($value);
        $cell = $this->cells[$x][$y];
        $cell->setValue($valueObject);

        $this->validate($cell);
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

    public function getStatus() : string
    {
        if ($this->dimension * 3 == $this->getCompletedSectionsCount()) {
            return "Complete";
        } elseif ( count($this->conflicts) > 0) {
            return "Error";
        } else {
            return "Pending";
        }
    }

    public function getCompletedSectionsCount() : int
    {
        return count($this->completedSections);
    }

    public function getConflicts() : array
    {
        return $this->conflicts;
    }

    public function getHash() : string
    {
        return $this->hash;
    }

    protected function validate(Cell $cell = null)
    {
        $this->conflicts = [];
        $cellSections = ($cell) ? $this->getCellSections($cell) : $this->getAllSections();
        foreach ($cellSections as $number){
            $this->validator->validate( $this->sections[$number] );
            if($this->validator->getStatus() == "Complete") {
                $this->addToCompletedSections($number);
            } else {
                $this->removeFromCompletedSections($number);
                $this->addToConflicts( $this->validator->getConflicts() );
            }
        }

    }

    protected function addToCompletedSections(int $number)
    {
        if(!in_array($number , $this->completedSections)){
            $this->completedSections[] = $number;
        }
    }

    protected function removeFromCompletedSections(int $number)
    {
        if(($key = array_search($number, $this->completedSections)) !== false) {
            unset($this->completedSections[$key]);
        }
    }

    protected function addToConflicts(array $conflicts)
    {
        foreach ($conflicts as $row) {
            $this->conflicts[$row['x']][$row['y']] = $row['message'];
        }
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

    protected function getAllSections(){
        for($i = 0 ; $i < $this->dimension * 3 ; $i++){
            $cellSections[] = $i;
        }
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