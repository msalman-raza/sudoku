<?php

namespace SudokuBundle\Library\Validator;


interface ValidatorInterface
{
    public function validate(array $section);
    public function getStatus() : string ;
    public function getConflicts() : array ;
    public function reset();
    public function getReport() : array ;
}