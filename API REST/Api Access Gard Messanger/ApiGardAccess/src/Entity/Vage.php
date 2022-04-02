<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VageRepository;

/**
 * @ORM\Entity(repositoryClass=VageRepository::class)
 */
class Vage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $semestre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Etudiants::class, inversedBy="vages",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $etudiant;

    public function tojson(): ?array
    {
        return $this ? [
            'id' => $this->id,
            'semestre' => $this->semestre,
            'date'=>$this->date,
            'etudiant'=>$this->etudiant?$this->etudiant->tojson():null,
        ] : null;
    }

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSemestre(): ?string
    {
        return $this->semestre;
    }

    public function setSemestre(string $semestre): self
    {
        $this->semestre = $semestre;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEtudiant(): ?Etudiants
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiants $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }
}
