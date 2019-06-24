<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity
 * @ApiResource()
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"game", "comment"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Groups({"game", "comment"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $password;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
     */
    private $userRoles;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas correctement confirmer votre mot de passe")
     */
    public $passwordConfirm;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_requests;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_request_max;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="creator")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GameSuggest", mappedBy="author")
     */
    private $gameSuggests;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    public function __construct()
    {
        $this->isActive = true;
        $this->userRoles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->gameSuggests = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return array('ROLE_USER');
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $roles =  $this->userRoles->map(function ($role){
            return $role->getTitle();
        })->toArray();
        $roles[] = 'ROLE_USER';
        return $roles;
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }
    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }
        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function getNbRequests(): ?int
    {
        return $this->nb_requests;
    }

    public function setNbRequests(int $nb_requests): self
    {
        $this->nb_requests = $nb_requests;

        return $this;
    }

    public function getNbRequestMax(): ?int
    {
        return $this->nb_request_max;
    }

    public function setNbRequestMax(int $nb_request_max): self
    {
        $this->nb_request_max = $nb_request_max;

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
            $comment->setCreator($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getCreator() === $this) {
                $comment->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GameSuggest[]
     */
    public function getGameSuggests(): Collection
    {
        return $this->gameSuggests;
    }

    public function addGameSuggest(GameSuggest $gameSuggest): self
    {
        if (!$this->gameSuggests->contains($gameSuggest)) {
            $this->gameSuggests[] = $gameSuggest;
            $gameSuggest->setAuthor($this);
        }

        return $this;
    }

    public function removeGameSuggest(GameSuggest $gameSuggest): self
    {
        if ($this->gameSuggests->contains($gameSuggest)) {
            $this->gameSuggests->removeElement($gameSuggest);
            // set the owning side to null (unless already changed)
            if ($gameSuggest->getAuthor() === $this) {
                $gameSuggest->setAuthor(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}