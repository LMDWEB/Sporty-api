<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\GameSuggestRepository")
 */
class GameSuggest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="gameSuggests")
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="gameSuggests")
     */
    private $author;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $scoreHomeTeam;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $scoreAwayTeam;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $isValid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getScoreHomeTeam(): ?int
    {
        return $this->scoreHomeTeam;
    }

    public function setScoreHomeTeam(?int $scoreHomeTeam): self
    {
        $this->scoreHomeTeam = $scoreHomeTeam;

        return $this;
    }

    public function getScoreAwayTeam(): ?int
    {
        return $this->scoreAwayTeam;
    }

    public function setScoreAwayTeam(?int $scoreAwayTeam): self
    {
        $this->scoreAwayTeam = $scoreAwayTeam;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIsValid(): ?int
    {
        return $this->isValid;
    }

    public function setIsValid(?int $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }
}
