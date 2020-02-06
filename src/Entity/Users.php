<?php
 
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 * @UniqueEntity(
 * fields={"mail"}, message="L'adresse mail indiquée est déjà utilisée", 
 * )
 *  @UniqueEntity( 
 * fields={"username"}, message="Pseudo déjà utilisé",
 * )
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=1, max=30,
     * minMessage = "Votre pseudo doit avoir au moins {{ limit }} caractères",
     * maxMessage = "Votre pseudo doit avoir au maximum {{ limit }} caractères"
     * )
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(min=1, max=30,
     * minMessage = "Votre pseudo doit avoir au moins {{ limit }} caractères",
     * maxMessage = "Votre pseudo doit avoir au maximum {{ limit }} caractères"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=8,
     * minMessage = "Votre mot de passe doit avoir au moins {{ limit }} caractères",
     * )

     */
    private $password;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Textes", mappedBy="username", orphanRemoval=true)
     */
    private $textes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Videos", mappedBy="username", orphanRemoval=true)
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaires", mappedBy="username", orphanRemoval=true)
     */
    private $commentaires;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function __construct()
    {
        $this->textes = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials() {}

    public function getSalt() {}

    /**
     * @return Collection|Textes[]
     */
    public function getTextes(): Collection
    {
        return $this->textes;
    }

    public function addTexte(Textes $texte): self
    {
        if (!$this->textes->contains($texte)) {
            $this->textes[] = $texte;
            $texte->setUsername($this);
        }

        return $this;
    }

    public function removeTexte(Textes $texte): self
    {
        if ($this->textes->contains($texte)) {
            $this->textes->removeElement($texte);
            // set the owning side to null (unless already changed)
            if ($texte->getUsername() === $this) {
                $texte->setUsername(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Videos[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Videos $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setUsername($this);
        }

        return $this;
    }

    public function removeVideo(Videos $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getUsername() === $this) {
                $video->setUsername(null);
            }
        }

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
            $commentaire->setUsername($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getUsername() === $this) {
                $commentaire->setUsername(null);
            }
        }

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
