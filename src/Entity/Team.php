<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 * @ApiResource()
 */
class Team
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"game"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"game"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"game"})
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="integer")
     */
    private $founded;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $venue_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $venue_capacity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\League", inversedBy="teams")
     */
    private $league;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="homeTeam")
     */
    private $games;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="awayTeam")
     */
    private $awayGames;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Player", mappedBy="team")
     */
    private $players;

    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->awayGames = new ArrayCollection();
        $this->players = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode(string $code)
    {
        $this->code = $code;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getFounded(): ?int
    {
        return $this->founded;
    }

    public function setFounded(int $founded): self
    {
        $this->founded = $founded;

        return $this;
    }

    public function getVenueName(): ?string
    {
        return $this->venue_name;
    }

    public function setVenueName(string $venue_name): self
    {
        $this->venue_name = $venue_name;

        return $this;
    }

    public function getVenueCapacity(): ?int
    {
        return $this->venue_capacity;
    }

    public function setVenueCapacity(int $venue_capacity): self
    {
        $this->venue_capacity = $venue_capacity;

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

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setHomeTeam($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getHomeTeam() === $this) {
                $game->setHomeTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getAwayGames(): Collection
    {
        return $this->awayGames;
    }

    public function addAwayGame(Game $awayGame): self
    {
        if (!$this->awayGames->contains($awayGame)) {
            $this->awayGames[] = $awayGame;
            $awayGame->setAwayTeam($this);
        }

        return $this;
    }

    public function removeAwayGame(Game $awayGame): self
    {
        if ($this->awayGames->contains($awayGame)) {
            $this->awayGames->removeElement($awayGame);
            // set the owning side to null (unless already changed)
            if ($awayGame->getAwayTeam() === $this) {
                $awayGame->setAwayTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            // set the owning side to null (unless already changed)
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }
}
