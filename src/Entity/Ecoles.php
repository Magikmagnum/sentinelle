<?php

namespace App\Entity;

use DateTime;
use DateTimeZone;
use DateTimeInterface;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EcolesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=EcolesRepository::class)
 */
class Ecoles
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ecole:new"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"ecole:new"})
     * @Assert\NotBlank
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"ecole:new"})
     */
    private $devise;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ecoles")
     */
    private $manager;

    /**
     * @ORM\OneToMany(targetEntity=Sessions::class, mappedBy="ecole")
     */
    private $sessions;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"ecole:new"})
     * @Assert\NotBlank
     * @var \DateTimeInterface
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->createdAt = new DateTime('now', new \DateTimeZone('Africa/Libreville'));
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

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(?string $devise): self
    {
        $this->devise = $devise;

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Collection|Sessions[]
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Sessions $session): self
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions[] = $session;
            $session->setEcole($this);
        }

        return $this;
    }

    public function removeSession(Sessions $session): self
    {
        if ($this->sessions->contains($session)) {
            $this->sessions->removeElement($session);
            // set the owning side to null (unless already changed)
            if ($session->getEcole() === $this) {
                $session->setEcole(null);
            }
        }

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
        $this->updatedAt = new DateTime('now', new \DateTimeZone('Africa/Libreville'));

        return $this;
    }
}
