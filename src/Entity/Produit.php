<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'produits', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categories = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagename = null;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
    public function __toString()
    {
        return $this->titre;
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

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setProduits($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getProduits() === $this) {
                $commentaire->setProduits(null);
            }
        }

        return $this;
    }

    public function getCategories(): ?Categorie
    {
        return $this->categories;
    }

    public function setCategories(?Categorie $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function getImagename(): ?string
    {
        return $this->imagename;
    }

    public function setImagename(?string $imagename): self
    {
        $this->imagename = $imagename;

        return $this;
    }
}
