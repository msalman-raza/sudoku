<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

class SudokuController extends FOSRestController
{

    /**
     * @Rest\Get("/sudoku/{hash}")
     */
    public function getGameAction(string $hash)
    {
        $object = $this->get('sudoku.root');
        $restresult = $object->getGame($hash);
        return $restresult;
    }

    /**
     * @Rest\Post("/sudoku/")
     */
    public function makeGameAction(Request $request)
    {
        $object = $this->get('sudoku.root');
        $restresult = $object->createGame();
        return $restresult;
    }

    /**
     * @Rest\Put("/sudoku/")
     */
    public function updateGameAction(Request $request)
    {
        $hash = $request->get('hash');
        $value = $request->get('value');
        $x = $request->get('x');
        $y = $request->get('y');
        $object = $this->get('sudoku.root');
        $restresult = $object->updateCell($hash , $value , $x , $y);
        return $restresult;
    }



    /**
     * @Rest\Delete("/sudoku/")
     */
    public function deleteGameAction(Request $request)
    {
        $hash = $request->get('hash');
        $object = $this->get('sudoku.root');
        $restresult = $object->deleteGame($hash);
        return $restresult;
    }

}
