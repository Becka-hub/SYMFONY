<?php

namespace App\Entity;

use App\Entity\Article;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="fk_category")
     */
    private $articles;

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


    public function tojson(bool $article=false): ?array
    {
        return $this ? [
            'id' => $this->id,
            'name' => $this->name,
            'active' =>$this->active,
            'articles'=> $article ? array_map(function(Article $article){
                return $article->tojson();
            },$this->articles->getValues()):[],
            'date_save'=>$this->date_save ? $this->date_save->format('c'):null,
            'date_updated'=>$this->date_updated ? $this->date_updated->format('c'):null, 
            'date_deleted'=>$this->date_deleted ? $this->date_deleted->format('c'):null,
        ] : null;
    }

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setFkCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getFkCategory() === $this) {
                $article->setFkCategory(null);
            }
        }

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
