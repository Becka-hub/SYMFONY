<?php

namespace App\Entity;

use App\Entity\Car;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
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
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=Car::class, inversedBy="comments",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $fk_car;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $fk_user;


    public function tojson(): ?array
    {
        return $this ? [
            'id' => $this->id,
            'comment' => $this->comment,
            'fk_car'=>$this->fk_car?$this->fk_car->tojson():null,
            'fk_user'=>$this->fk_user?$this->fk_user->tojson():null,
        ] : null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getFkCar(): ?Car
    {
        return $this->fk_car;
    }

    public function setFkCar(?Car $fk_car): self
    {
        $this->fk_car = $fk_car;

        return $this;
    }

    public function getFkUser(): ?User
    {
        return $this->fk_user;
    }

    public function setFkUser(?User $fk_user): self
    {
        $this->fk_user = $fk_user;

        return $this;
    }
}
