<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
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
    private $author;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=Etudiants::class, inversedBy="messageSender",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity=Etudiants::class, inversedBy="messageReceiver",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $receiver;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isView;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function tojson(): ?array
    {
        return $this ? [
            'id' => $this->id,
            'author' => $this->author,
            'message'=>$this->message,
            'isView'=>$this->isView,
            'created_at'=>$this->created_at,
            'sender'=>$this->sender?$this->sender->tojson():null,
            'receiver'=>$this->receiver?$this->receiver->tojson():null
        ] : null;
    }

    public function __construct()
    {
        $this->created_at= new \DateTime();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getSender(): ?Etudiants
    {
        return $this->sender;
    }

    public function setSender(?Etudiants $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?Etudiants
    {
        return $this->receiver;
    }

    public function setReceiver(?Etudiants $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getIsView(): ?bool
    {
        return $this->isView;
    }

    public function setIsView(bool $isView): self
    {
        $this->isView = $isView;

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
}
