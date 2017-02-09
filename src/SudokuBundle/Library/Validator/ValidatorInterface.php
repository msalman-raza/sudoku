<?php

namespace SudokuBundle\Library\Validator;


interface ValidatorInterface
{
    /**
     * Validate section for repetition and also checks if cell is complete
     *
     * @param  array $section Cells array
     *
     */
    public function validate(array $section);

    /**
     * Get status after validations
     *
     * @return  string
     *
     */
    public function getStatus() : string ;

    /**
     * Get conflicts after validation
     *
     * @return  array
     *
     */
    public function getConflicts() : array ;

    /**
     * restes filters and values
     *
     */
    public function reset();

    /**
     * Get complete report
     *
     * @return  array
     *
     */
    public function getReport() : array ;
}