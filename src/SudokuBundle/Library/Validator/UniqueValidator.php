<?php

namespace SudokuBundle\Library\Validator;


use SudokuBundle\Library\Cell;

class UniqueValidator implements ValidatorInterface
{
    protected $message = "Repetition";
    protected $conflicts = [];
    protected $emptyFlag = false;
    protected $repetitionFlag = false;

    public function validate(array $section)
    {
        $this->reset();
        $temp = [];

        foreach($section as $key=>$cell){
            if($cell->getValue() != ""){
                if(isset($temp[$cell->getValue()])) {
                    $this->addToConflict($key  ,$cell);
                    $this->addToConflict($temp[$cell->getValue()] , $section[$temp[$cell->getValue()]]);
                } else {
                    $temp[ $cell->getValue() ]  = $key;
                }
            } else {
                $this->emptyFlag = true;
            }
        }
    }

    public function reset()
    {
        $this->conflicts = [];
        $this->emptyFlag = false;
        $this->repetitionFlag = false;
    }
    public function getStatus() : string
    {
        if($this->repetitionFlag) {
            return "Error";
        } elseif($this->emptyFlag) {
            return "Pending";
        }
        return "Complete";
    }

    public function getConflicts() : array
    {
        return $this->conflicts;
    }

    public function getReport() : array
    {
        $report['status'] = $this->getStatus();
        $report['conflicts'] = $this->getConflicts();
    }
    protected function addToConflict($key , Cell $cell)
    {
        $this->conflicts[$key]['message'] = $this->message;
        $this->conflicts[$key]['x'] = $cell->getX();
        $this->conflicts[$key]['y'] = $cell->getY();
        $this->repetitionFlag = true;
    }
}