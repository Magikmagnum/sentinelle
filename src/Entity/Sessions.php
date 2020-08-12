<?php

namespace App\Entity;

use App\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SessionsRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=SessionsRepository::class)
 */
class Sessions extends Entity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"session:new"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"session:new"})
     */
    private $nom;

    /**
     * @ORM\Column(type="date")
     * @Groups({"session:new"})
     */
    private $debut_at;
    
    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"session:new"})
     */
    private $fin_at;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"session:new"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=Ecoles::class, inversedBy="sessions")
     */
    private $ecole;

    /**
     * @ORM\OneToMany(targetEntity=Classes::class, mappedBy="session")
     */
    private $classes;


    public function __construct()
    {
        $this->classes = new ArrayCollection();
        $this->created_at = $this->getDatime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDebutAt(): ?\DateTimeInterface
    {
        return $this->debut_at;
    }

    public function setDebutAt(\DateTimeInterface $debut_at): self
    {
        $this->debut_at = $debut_at;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(): self
    {
        $this->updated_at = $this->getDatime('now');

        return $this;
    }

    public function getEcole(): ?Ecoles
    {
        return $this->ecole;
    }

    public function setEcole(?Ecoles $ecole): self
    {
        $this->ecole = $ecole;

        return $this;
    }

    /**
     * @return Collection|Classes[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classes $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes[] = $class;
            $class->setSession($this);
        }

        return $this;
    }

    public function removeClass(Classes $class): self
    {
        if ($this->classes->contains($class)) {
            $this->classes->removeElement($class);
            // set the owning side to null (unless already changed)
            if ($class->getSession() === $this) {
                $class->setSession(null);
            }
        }

        return $this;
    }

    public function getFinAt(): ?\DateTimeInterface
    {
        return $this->fin_at;
    }

    public function setFinAt(?\DateTimeInterface $fin_at): self
    {
        $this->fin_at = $fin_at;

        return $this;
    }
}
