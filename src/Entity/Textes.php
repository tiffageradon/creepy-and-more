<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TextesRepository")
 */
class Textes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(min=1, max=100,
     * minMessage= "Vous devez avoir un titre avec au moins {{ limit }} caractère",
     * maxMessage= "Vous devez avoir un titre avec maximum {{ limit }} caractères"
     * )

     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(min=1, max=100,
     * minMessage= "Vous devez avoir un titre avec au moins {{ limit }} caractère",
     * maxMessage= "Vous devez avoir un titre avec maximum {{ limit }} caractères"
     * )

     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=10,
     * minMessage = "Votre commentaire doit faire au moins {{ limit }} caractères"
     * )

     */
    private $texte;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     */
    private $signalement;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="textes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaires", mappedBy="idtexte")
     */
    private $commentaires;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $del;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

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

    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setIdtexte($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdtexte() === $this) {
                $commentaire->setIdtexte(null);
            }
        }

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
