<?php

namespace SudokuBundle\Library\Validator;


use SudokuBundle\Library\Cell;

class UniqueValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    protected $message = "Repetition";

    /**
     * @var array
     */
    protected $conflicts = [];

    /**
     * @var bool
     */
    protected $emptyFlag = false;

    /**
     * @var bool
     */
    protected $repetitionFlag = false;



    /**
     * Validate section for repetition and also checks if cell is complete
     *
     * @param  array $section Cells array
     *
     */
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

    /**
     * restes filters and values
     *
     */
    public function reset()
    {
        $this->conflicts = [];
        $this->emptyFlag = false;
        $this->repetitionFlag = false;
    }

    /**
     * Get status after validations
     *
     * @return  string
     *
     */
    public function getStatus() : string
    {
        if($this->repetitionFlag) {
            return "Error";
        } elseif($this->emptyFlag) {
            return "Pending";
        }
        return "Complete";
    }

    /**
     * Get conflicts after validation
     *
     * @return  array
     *
     */
    public function getConflicts() : array
    {
        return $this->conflicts;
    }

    /**
     * Get complete report
     *
     * @return  array
     *
     */
    public function getReport() : array
    {
        $report['status'] = $this->getStatus();
        $report['conflicts'] = $this->getConflicts();
    }

    /**
     * Get complete report
     *
     * @param  int $key Cell index in aray
     * @param Cell $cell
     *
     */
    protected function addToConflict($key , Cell $cell)
    {
        $this->conflicts[$key]['message'] = $this->message;
        $this->conflicts[$key]['x'] = $cell->getX();
        $this->conflicts[$key]['y'] = $cell->getY();
        $this->repetitionFlag = true;
    }
}