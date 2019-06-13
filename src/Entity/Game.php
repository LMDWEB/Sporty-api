<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 * @ApiResource(normalizationContext={"groups"={"game"}}, attributes={"pagination_items_per_page"=3})
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"game"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"game"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="games")
     * @Groups({"game"})
     */
    private $homeTeam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="awayGames")
     * @Groups({"game"})
     */
    private $awayTeam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\League", inversedBy="games")
     * @Groups({"game"})
     */
    private $league;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Groups({"game"})
     */
    private $score;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="game")
     * @Groups({"game"})
     */
    private $comments;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"game"})
     */
    private $goalsHomeTeam;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"game"})
     */
    private $goalsAwayTeam;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"game"})
     */
    private $eventStart;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"game"})
     */
    private $eventBegin;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getHomeTeam(): ?Team
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(?Team $homeTeam): self
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    public function getAwayTeam(): ?Team
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(?Team $awayTeam): self
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    public function getLeague(): ?League
    {
        return $this->league;
    }

    public function setLeague(?League $league): self
    {
        $this->league = $league;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(?string $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setGame($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getGame() === $this) {
                $comment->setGame(null);
            }
        }

        return $this;
    }

    public function getGoalsHomeTeam(): ?int
    {
        return $this->goalsHomeTeam;
    }

    public function setGoalsHomeTeam(?int $goalsHomeTeam): self
    {
        $this->goalsHomeTeam = $goalsHomeTeam;

        return $this;
    }

    public function getGoalsAwayTeam(): ?int
    {
        return $this->goalsAwayTeam;
    }

    public function setGoalsAwayTeam(?int $goalsAwayTeam): self
    {
        $this->goalsAwayTeam = $goalsAwayTeam;

        return $this;
    }

    public function getEventStart(): ?int
    {
        return $this->eventStart;
    }

    public function setEventStart(?int $eventStart): self
    {
        $this->eventStart = $eventStart;

        return $this;
    }

    public function getEventBegin(): ?string
    {
        return $this->eventBegin;
    }

    public function setEventBegin(?string $eventBegin): self
    {
        $this->eventBegin = $eventBegin;

        return $this;
    }
}
