<?php

namespace App\Entity;

use App\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClassesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=ClassesRepository::class)
 */
class Classes extends Entity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @Groups({"classe:new"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"classe:new"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @Groups({"classe:new"})
     * @ORM\Column(type="integer")
     */
    private $classe;

    /**
     * @Groups({"classe:new"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $examen;

    /**
     * @Groups({"classe:new"})
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Sessions::class, inversedBy="classes")
     */
    private $session;

    /**
     * @ORM\OneToMany(targetEntity=Eleves::class, mappedBy="classe")
     */
    private $eleves;

    /**
     * @ORM\ManyToMany(targetEntity=Professeurs::class, mappedBy="classes")
     */
    private $professeurs;

    /**
     * @ORM\OneToMany(targetEntity=Evaluations::class, mappedBy="classe")
     */
    private $evaluations;

    public function __construct()
    {
        $this->eleves = new ArrayCollection();
        $this->professeurs = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
        $this->createdAt = $this->getDatime('now');
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

    public function getClasse(): ?int
    {
        return $this->classe;
    }

    public function setClasse(int $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function getExamen(): ?bool
    {
        return $this->examen;
    }

    public function setExamen(?bool $examen): self
    {
        $this->examen = $examen;

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
        $this->updatedAt = $this->getDatime('now');

        return $this;
    }

    public function getSession(): ?Sessions
    {
        return $this->session;
    }

    public function setSession(?Sessions $session): self
    {
        $this->session = $session;

        return $this;
    }

    /**
     * @return Collection|Eleves[]
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addElefe(Eleves $elefe): self
    {
        if (!$this->eleves->contains($elefe)) {
            $this->eleves[] = $elefe;
            $elefe->setClasse($this);
        }

        return $this;
    }

    public function removeElefe(Eleves $elefe): self
    {
        if ($this->eleves->contains($elefe)) {
            $this->eleves->removeElement($elefe);
            // set the owning side to null (unless already changed)
            if ($elefe->getClasse() === $this) {
                $elefe->setClasse(null);
            }
        }

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
            $professeur->addClass($this);
        }

        return $this;
    }

    public function removeProfesseur(Professeurs $professeur): self
    {
        if ($this->professeurs->contains($professeur)) {
            $this->professeurs->removeElement($professeur);
            $professeur->removeClass($this);
        }

        return $this;
    }

    /**
     * @return Collection|Evaluations[]
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluations $evaluation): self
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations[] = $evaluation;
            $evaluation->setClasse($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluations $evaluation): self
    {
        if ($this->evaluations->contains($evaluation)) {
            $this->evaluations->removeElement($evaluation);
            // set the owning side to null (unless already changed)
            if ($evaluation->getClasse() === $this) {
                $evaluation->setClasse(null);
            }
        }

        return $this;
    }
}
