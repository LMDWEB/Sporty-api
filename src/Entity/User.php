<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
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

    public function __construct()
    {
        $this->isActive = true;
        $this->userRoles = new ArrayCollection();
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
    public function getUserRoles(): Collection
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
}