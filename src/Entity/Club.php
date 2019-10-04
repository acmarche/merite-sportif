<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClubRepository")
 */
class Club
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=130)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vote", mappedBy="club", orphanRemoval=true)
     */
    private $votes;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="club", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var bool
     */
    private $voteIsComplete = false;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getToken()
    {
        if (!$user = $this->getUser()) {
            return null;
        }

        if (!$token = $this->getUser()->getToken()) {
            return null;
        }

        return $token->getValue();
    }

    /**
     * @return bool
     */
    public function isVoteIsComplete(): bool
    {
        return $this->voteIsComplete;
    }

    /**
     * @param bool $voteIsComplete
     */
    public function setVoteIsComplete(bool $voteIsComplete): void
    {
        $this->voteIsComplete = $voteIsComplete;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Vote[]
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setClub($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->contains($vote)) {
            $this->votes->removeElement($vote);
            // set the owning side to null (unless already changed)
            if ($vote->getClub() === $this) {
                $vote->setClub(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

}
