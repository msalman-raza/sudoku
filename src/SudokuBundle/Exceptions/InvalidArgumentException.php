<?php namespace SudokuBundle\Exceptions;


use Exception;

class InvalidArgumentException extends \Exception
{
    public function __construct($message = null)
    {

        $message = $message ?? "Invalid Argument";
        parent::__construct($message, 400, null);
    }

}