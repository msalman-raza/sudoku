<?php namespace SudokuBundle\Exceptions;


use Exception;

class AccessDeniedException extends \Exception
{
    public function __construct()
    {
        $message = "Cell is locked.";
        parent::__construct($message, 400, null);
    }

}