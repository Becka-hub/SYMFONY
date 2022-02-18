<?php

namespace App\Entity;

use App\Entity\Category;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Login::class, inversedBy="articles")
     */
    private $fk_login;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="articles")
     */
    private $fk_category;
        /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_save;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_updated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_deleted;

    public function tojson(): ?array
    {
        return $this ? [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'fk_login'=>$this->fk_login?$this->fk_login->tojson():null,
            'fk_category'=>$this->fk_category?$this->fk_category->tojson():null,
            'active' =>$this->active,
            'date_save'=>$this->date_save ? $this->date_save->format('c'):null,
            'date_updated'=>$this->date_updated ? $this->date_updated->format('c'):null, 
            'date_deleted'=>$this->date_deleted ? $this->date_deleted->format('c'):null,
        ] : null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getFkLogin(): ?Login
    {
        return $this->fk_login;
    }

    public function setFkLogin(?Login $fk_login): self
    {
        $this->fk_login = $fk_login;

        return $this;
    }

    public function getFkCategory(): ?Category
    {
        return $this->fk_category;
    }

    public function setFkCategory(?Category $fk_category): self
    {
        $this->fk_category = $fk_category;

        return $this;
    }
    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getDateSave(): ?\DateTimeInterface
    {
        return $this->date_save;
    }

    public function setDateSave(?\DateTimeInterface $date_save): self
    {
        $this->date_save = $date_save;

        return $this;
    }

    public function getDateUpdated(): ?\DateTimeInterface
    {
        return $this->date_updated;
    }

    public function setDateUpdated(?\DateTimeInterface $date_updated): self
    {
        $this->date_updated = $date_updated;

        return $this;
    }

    public function getDateDeleted(): ?\DateTimeInterface
    {
        return $this->date_deleted;
    }

    public function setDateDeleted(?\DateTimeInterface $date_deleted): self
    {
        $this->date_deleted = $date_deleted;

        return $this;
    }
}
