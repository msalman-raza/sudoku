<?php
/**
 * Created by PhpStorm.
 * User: salmanraza
 * Date: 2/7/17
 * Time: 3:06 PM
 */

namespace SudokuBundle\Root;



use Doctrine\ORM\EntityManager;
use SudokuBundle\Entity\SudokuGame;
use SudokuBundle\Exceptions\InvalidArgumentException;
use SudokuBundle\Library\Board;
use SudokuBundle\Repository\SudokuGameRepository;
use SudokuBundle\Resources\Games\SampleGame;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Game
{
    use ContainerAwareTrait;

    protected $em;
    protected $repository;
    protected $board;


    public function __construct(
        EntityManager $em ,
        SudokuGameRepository $repository,
        Board $board
    )
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->board = $board;
    }

    public function createGame() : array
    {
        $data = SampleGame::getGame();
        $this->board->makeNewGame($data);
        $game = $this->board->getGameAsArray();


        $entity = new SudokuGame();
        $gameString = json_encode($game);
        $entity->setHash($this->board->getHash());
        $entity->setLockedGame($gameString);
        $entity->setGame($gameString);

        $this->em->persist($entity);
        $this->em->flush();

        $returnData['hash'] = $this->board->getHash();
        $returnData['game'] = $game;
        return $returnData;
    }

    public function updateCell(string $hash, $value, int $x, int $y)
    {
        $entity = $this->makeExistingGame($hash);
        $this->board->setCell($value , $x , $y);
        $game = $this->board->getGameAsArray();

        $gameString = json_encode($game);
        $entity->setGame($gameString);
        $this->em->persist($entity);
        $this->em->flush();

        $returnData['status'] = $this->board->getStatus();
        $returnData['conflicts'] = $this->board->getConflicts();
        return $returnData;
    }

    public function getGame(string $hash)
    {

        $this->makeExistingGame($hash);
        $game = $this->board->getGameAsArray();
        $lockedGame = $this->board->getLockedGameAsArray();

        $returnData['game'] = $game;
        $returnData['lockedGame'] = $lockedGame;
        $returnData['status'] = $this->board->getStatus();
        $returnData['conflicts'] = $this->board->getConflicts();
        return $returnData;
    }

    public function deleteGame(string $hash)
    {

        $entity = $this->getEntity($hash);
        $this->em->remove($entity);
        $this->em->flush();

        $returnData['status'] = "Done";
        return $returnData;
    }


    protected function makeExistingGame(String $hash) : SudokuGame
    {
        $entity = $this->getEntity($hash);
        $lockedGame = json_decode($entity->getLockedGame());
        $actualGame = json_decode($entity->getGame());
        $this->board->makeExistingGame($lockedGame , $actualGame);
        return $entity;
    }

    protected function getEntity(String $hash) : SudokuGame
    {
        $entity = $this->repository->findOneByHash($hash);
        if($entity) {
            return $entity;
        }
        throw new InvalidArgumentException("Invalid hash");
    }
}

