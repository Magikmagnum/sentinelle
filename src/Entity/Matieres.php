<?php

namespace App\Entity;

use App\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MatieresRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MatieresRepository::class)
 */
class Matieres extends Entity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @Groups({"matiere:new"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"matiere:new"})
     * @ORM\Column(type="string", length=255)
     */
    private $matiere;

    /**
     * @Groups({"matiere:new"})
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @Groups({"matiere:new"})
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $abreger;

    /**
     * @ORM\OneToMany(targetEntity=Professeurs::class, mappedBy="matiere")
     */
    private $professeurs;

    public function __construct()
    {
        $this->professeurs = new ArrayCollection();
        $this->createdAt = $this->getDatime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): self
    {
        $this->matiere = $matiere;

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

    public function setUpdatedAt(): self
    {
        $this->updatedAt = $this->getDatime();

        return $this;
    }

    public function getAbreger(): ?string
    {
        return $this->abreger;
    }

    public function setAbreger(?string $abreger): self
    {
        $this->abreger = $abreger;

        return $this;
    }

    /**
     * @return Collection|Professeurs[]
     */
    public function getProfesseurs(): Collection
    {
        return $this->professeurs;
    }

    public function addProfesseur(Professeurs $professeur): self
    {
        if (!$this->professeurs->contains($professeur)) {
            $this->professeurs[] = $professeur;
            $professeur->setMatiere($this);
        }

        return $this;
    }

    public function removeProfesseur(Professeurs $professeur): self
    {
        if ($this->professeurs->contains($professeur)) {
            $this->professeurs->removeElement($professeur);
            // set the owning side to null (unless already changed)
            if ($professeur->getMatiere() === $this) {
                $professeur->setMatiere(null);
            }
        }

        return $this;
    }
}
