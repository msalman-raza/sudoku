<?php
namespace SudokuBundle\Library;


use SudokuBundle\Exceptions\InvalidArgumentException;
use SudokuBundle\Library\Validator\ValidatorInterface;
use SudokuBundle\Library\Value\ValueInterface;

class Board
{
    /**
     * @var string
     */
    protected $hash;

    /**
     * @var Cell[][]
     */
    protected $cells;

    /**
     * @var string Classname
     */
    protected $valueClass;

    /**
     * @var int
     */
    protected $dimension;

    /**
     * @var Cell[]
     */
    protected $sections;

    /**
     * @var int[]
     */
    protected $completedSections = [];

    /**
     * @var array
     */
    protected $conflicts;

    /**
     * @var ValidatorInterface
     */
    protected $validator;


    /**
     * Create a new Sudoku board
     *
     * @param  string   $valueClass Classname for value class
     * @param  int  $dimension Sudoku board size
     * @param  ValidatorInterface  $validator Validator to validate board sections
     *
     * @return Board
     */
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


    /**
     * Makes new game
     *
     * @param array $data 2D array of intial game
     */
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

    /**
     * Makes existing game
     *
     * @param array $lockedData 2D array of locked game
     * @param array $data 2D array of actual game
     *
     */
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

    /**
     * Sets value in cell
     *
     * @param mixed $value value to be stored in cell
     * @param int $x row # in board
     * @param int $y column # in board
     *
     */
    public function setCell($value , int $x , int $y)
    {
        $valueObject = new $this->valueClass($value);
        $cell = $this->cells[$x][$y];
        $cell->setValue($valueObject);

        $this->validate($cell);
    }

    /**
     * Get game data as array
     *
     * @return array 2D array of game data
     *
     */
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

    /**
     * Get game locked data as array
     *
     * @return array 2D array of game data
     *
     */
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

    /**
     * Get game status
     *
     * @return string
     *
     */
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

    /**
     * Get completed sections [used in testing]
     *
     * @return int
     *
     */
    public function getCompletedSectionsCount() : int
    {
        return count($this->completedSections);
    }

    /**
     * Get game conflicts
     *
     * @return array
     *
     */
    public function getConflicts() : array
    {
        return $this->conflicts;
    }

    /**
     * Get game hash
     *
     * @return string
     *
     */
    public function getHash() : string
    {
        return $this->hash;
    }

    /**
     * Get game status
     *
     * @param Cell $cell Optional Cell for which value is changed
     *
     */
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

    /**
     * Add to completed Sections
     *
     * @param int $number Section number
     *
     */
    protected function addToCompletedSections(int $number)
    {
        if(!in_array($number , $this->completedSections)){
            $this->completedSections[] = $number;
        }
    }

    /**
     * remove from completed Sections
     *
     * @param int $number Section number
     *
     */
    protected function removeFromCompletedSections(int $number)
    {
        if(($key = array_search($number, $this->completedSections)) !== false) {
            unset($this->completedSections[$key]);
        }
    }

    /**
     * Add to conflicts
     *
     * @param array $conflicts
     *
     */
    protected function addToConflicts(array $conflicts)
    {
        foreach ($conflicts as $row) {
            $this->conflicts[$row['x']][$row['y']] = $row['message'];
        }
    }

    /**
     * Sets and validates  Value class
     *
     * @param string $valueClass Classname
     *
     * @throws \TypeError if classname is not of correct type
     *
     */
    protected function setValueClass(string $valueClass)
    {
        if(is_a($valueClass,'SudokuBundle\Library\Value\ValueInterface',true)){
            $this->valueClass = $valueClass;
        }else{
            throw new \TypeError("Invalid class");
        }
    }

    /**
     * Sets and validates  Value class
     *
     * @param int $dimension
     *
     * @throws InvalidArgumentException if dimension is not valid
     *
     */
    protected function setDimension(int $dimension)
    {
        $class = $this->valueClass;
        if($class::getHeight() == $dimension && $this->isPerfectSquare($dimension)){
            $this->dimension = $dimension;
        }else{
            throw new InvalidArgumentException("Invalid dimension.");
        }
    }

    /**
     * Checks if number is a perfect suqare
     *
     * @param int $dimension
     *
     * @return bool
     *
     */
    protected function isPerfectSquare(int $dimension) : bool
    {
        return sqrt($dimension) == floor(sqrt($dimension));
    }

    /**
     * Makes value object from mixed value
     *
     * @param mixed $primaryValue
     * @param mixed $secondaryValue
     *
     * @return ValueInterface | null
     *
     */
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

    /**
     * Adds cell to board and appropriate sections
     *
     * @param Cell $cell
     *
     */
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

    /**
     * Get cell associate sections
     *
     * @return  array
     *
     */
    protected function getCellSections(Cell $cell) : array
    {
        $cellSections[] = $cell->getX();
        $cellSections[] = $this->dimension + $cell->getY();
        $cellSections[] = ($this->dimension * 2) + $this->getRegion($cell);
        return $cellSections;
    }

    /**
     * Get all sections
     *
     * @return  array
     *
     */
    protected function getAllSections() : array
    {
        for($i = 0 ; $i < $this->dimension * 3 ; $i++){
            $cellSections[] = $i;
        }
        return $cellSections;
    }

    /**
     * Get region number of cell
     *
     * @param Cell $cell
     * @return  int
     *
     */
    protected function getRegion(Cell $cell) : int
    {
        $length = sqrt($this->dimension);
        $regionRow = floor($cell->getX() / $length);
        $regionCol = floor($cell->getY() / $length);
        return ($regionRow * $length) + $regionCol;
    }

    /**
     * Makes hash
     *
     */
    protected function makeHash()
    {
        $this->hash = substr( md5(rand()), 0, 15);
    }

}