<?php

namespace App\Entity; 

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentairesRepository")
 */
class Commentaires
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=10,
     * minMessage = "Votre commentaire doit faire au moins {{ limit }} caractères"
     * )

     */
    private $texte;

    /**
     * @ORM\Column(type="integer")
     */
    private $signalement;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $username;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Textes", inversedBy="commentaires")
     */
    private $idtexte;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Videos", inversedBy="commentaires")
     */
    private $idvideo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $del;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    public function getSignalement(): ?int
    {
        return $this->signalement;
    }

    public function setSignalement(int $signalement): self
    {
        $this->signalement = $signalement;

        return $this;
    }

    public function getUsername(): ?Users
    {
        return $this->username;
    }

    public function setUsername(?Users $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getIdtexte(): ?Textes
    {
        return $this->idtexte;
    }

    public function setIdtexte(?Textes $idtexte): self
    {
        $this->idtexte = $idtexte;

        return $this;
    }

    public function getIdvideo(): ?Videos
    {
        return $this->idvideo;
    }

    public function setIdvideo(?Videos $idvideo): self
    {
        $this->idvideo = $idvideo;

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

    public function getDel(): ?int
    {
        return $this->del;
    }

    public function setDel(?int $del): self
    {
        $this->del = $del;

        return $this;
    }
}
