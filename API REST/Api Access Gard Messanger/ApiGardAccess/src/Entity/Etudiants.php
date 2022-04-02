<?php

namespace App\Entity;

use App\Entity\Vage;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EtudiantsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=EtudiantsRepository::class)
 */
class Etudiants
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresseMail;

    /**
     * @ORM\OneToMany(targetEntity=Vage::class, mappedBy="etudiant")
     */
    private $vages;

    /**
     * @ORM\OneToMany(targetEntity=Mail::class, mappedBy="Etudiant")
     */
    private $mails;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sender")
     */
    private $messageSender;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="receiver")
     */
    private $messageReceiver;


    
    public function tojson(bool $vage=false,bool $sender=false,bool $receiver=false): ?array
    {
        return $this ? [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' =>$this->prenom,
            'email'=>$this->adresseMail,
            'vages'=> $vage ? array_map(function(Vage $vage){
                return $vage->tojson();
            },$this->vages->getValues()):[],
            'messageSender'=> $sender ? array_map(function(Message $message){
                return $message->tojson();
            },$this->messageSender->getValues()):[],
            'messageReceiver'=> $receiver ? array_map(function(Message $message){
                return $message->tojson();
            },$this->messageReceiver->getValues()):[],
        ] : null;
    }

    public function __construct()
    {
        $this->vages = new ArrayCollection();
        $this->mails = new ArrayCollection();
        $this->messageSender = new ArrayCollection();
        $this->messageReceiver = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresseMail(): ?string
    {
        return $this->adresseMail;
    }

    public function setAdresseMail(string $adresseMail): self
    {
        $this->adresseMail = $adresseMail;

        return $this;
    }

    /**
     * @return Collection|Vage[]
     */
    public function getVages(): Collection
    {
        return $this->vages;
    }

    public function addVage(Vage $vage): self
    {
        if (!$this->vages->contains($vage)) {
            $this->vages[] = $vage;
            $vage->setEtudiant($this);
        }

        return $this;
    }

    public function removeVage(Vage $vage): self
    {
        if ($this->vages->removeElement($vage)) {
            // set the owning side to null (unless already changed)
            if ($vage->getEtudiant() === $this) {
                $vage->setEtudiant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Mail[]
     */
    public function getMails(): Collection
    {
        return $this->mails;
    }

    public function addMail(Mail $mail): self
    {
        if (!$this->mails->contains($mail)) {
            $this->mails[] = $mail;
            $mail->setEtudiant($this);
        }

        return $this;
    }

    public function removeMail(Mail $mail): self
    {
        if ($this->mails->removeElement($mail)) {
            // set the owning side to null (unless already changed)
            if ($mail->getEtudiant() === $this) {
                $mail->setEtudiant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessageSender(): Collection
    {
        return $this->messageSender;
    }

    public function addMessageSender(Message $messageSender): self
    {
        if (!$this->messageSender->contains($messageSender)) {
            $this->messageSender[] = $messageSender;
            $messageSender->setSender($this);
        }

        return $this;
    }

    public function removeMessageSender(Message $messageSender): self
    {
        if ($this->messageSender->removeElement($messageSender)) {
            // set the owning side to null (unless already changed)
            if ($messageSender->getSender() === $this) {
                $messageSender->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessageReceiver(): Collection
    {
        return $this->messageReceiver;
    }

    public function addMessageReceiver(Message $messageReceiver): self
    {
        if (!$this->messageReceiver->contains($messageReceiver)) {
            $this->messageReceiver[] = $messageReceiver;
            $messageReceiver->setReceiver($this);
        }

        return $this;
    }

    public function removeMessageReceiver(Message $messageReceiver): self
    {
        if ($this->messageReceiver->removeElement($messageReceiver)) {
            // set the owning side to null (unless already changed)
            if ($messageReceiver->getReceiver() === $this) {
                $messageReceiver->setReceiver(null);
            }
        }

        return $this;
    }

}
