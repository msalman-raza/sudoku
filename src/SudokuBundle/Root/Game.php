<?php
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
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var SudokuGameRepository
     */
    protected $repository;

    /**
     * @var Board
     */
    protected $board;


    /**
     * Create a new game object
     *
     * @param  EntityManager   $em
     * @param  SudokuGameRepository  $repository
     * @param  Board  $board
     *
     * @return Game
     */
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

    /**
     * Creates the game.
     *
     * @return array
     */
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

    /**
     * Updates the cell of board
     *
     * @param string $hash hash of game, returned on game creation
     * @param $value new value of cell
     * @param int $x Row of board
     * @param int $y Column of board
     *
     * @return array
     */
    public function updateCell(string $hash, $value, int $x, int $y) : array
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

    /**
     * Get complete game
     *
     * @param string $hash hash of game, returned on game creation
     *
     * @return array
     */
    public function getGame(string $hash) : array
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

    /**
     * Deletes the game
     *
     * @param string $hash hash of game, returned on game creation
     *
     * @return array
     */
    public function deleteGame(string $hash) : array
    {

        $entity = $this->getEntity($hash);
        $this->em->remove($entity);
        $this->em->flush();

        $returnData['status'] = "Done";
        return $returnData;
    }

    /**
     * Crates the game from database, used in updateCell and getGame functions
     *
     * @param string $hash hash of game, returned on game creation
     *
     * @return SudokuGame
     */
    protected function makeExistingGame(string $hash) : SudokuGame
    {
        $entity = $this->getEntity($hash);
        $lockedGame = json_decode($entity->getLockedGame());
        $actualGame = json_decode($entity->getGame());
        $this->board->makeExistingGame($lockedGame , $actualGame);
        return $entity;
    }

    /**
     * Returns the SuduokuGame entity, also throws error if not found
     *
     * @param string $hash hash of game, returned on game creation
     *
     * @throws InvalidArgumentException if hash not found
     *
     * @return SudokuGame
     */
    protected function getEntity(string $hash) : SudokuGame
    {
        $entity = $this->repository->findOneByHash($hash);
        if($entity) {
            return $entity;
        }
        throw new InvalidArgumentException("Invalid hash");
    }
}

