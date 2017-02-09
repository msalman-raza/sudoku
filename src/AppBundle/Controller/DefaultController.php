<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SudokuBundle\Root;

class DefaultController extends Controller
{
    protected $sudokuObject;
    public function __construct()
    {

    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $object = $this->get('sudoku.root');
        $hash = 'f5c4262d602a4a4';
        $hash = 'ebb0dfc2b22ecaf';
        $data = $object->updateCell($hash , 6, 0 ,3);
       // $data = $object->deleteGame($hash);
        print_r($data);
        exit();
    }
}
