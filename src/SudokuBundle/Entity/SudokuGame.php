<?php

namespace SudokuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SudokuGame
 *
 * @ORM\Table(name="sudoku_game")
 * @ORM\Entity(repositoryClass="SudokuBundle\Repository\SudokuGameRepository")
 */
class SudokuGame
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=25, nullable=true, unique=true)
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="game", type="string", length=500)
     */
    private $game;

    /**
     * @var string
     *
     * @ORM\Column(name="lockedGame", type="string", length=500)
     */
    private $lockedGame;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return SudokuGame
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set game
     *
     * @param string $game
     *
     * @return SudokuGame
     */
    public function setGame($game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return string
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set lockedGame
     *
     * @param string $lockedGame
     *
     * @return SudokuGame
     */
    public function setLockedGame($lockedGame)
    {
        $this->lockedGame = $lockedGame;

        return $this;
    }

    /**
     * Get lockedGame
     *
     * @return string
     */
    public function getLockedGame()
    {
        return $this->lockedGame;
    }
}

