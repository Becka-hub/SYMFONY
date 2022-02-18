<?php

namespace App\Entity;

use App\Repository\CrudRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=CrudRepository::class)
 */
class Crud
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
    #[Assert\NotBlank(message:"le champ de text est requi$
    red")]
    #[Assert\Length(min:5,max:12,minMessage:"Le text doit être superieur a 5",maxMessage:"Le text doit être superieur a 12")]
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    #[Assert\NotBlank]
    private $content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
