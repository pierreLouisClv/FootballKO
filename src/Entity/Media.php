<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $altText = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\OneToMany(mappedBy: 'media', targetEntity: Article::class)]
    private Collection $associatedArticles;

    #[ORM\OneToMany(mappedBy: 'media', targetEntity: InjuryArticle::class)]
    private Collection $injuryArticles;

    #[ORM\ManyToOne(inversedBy: 'medias')]
    private ?Championship $associatedChampionship = null;

    #[ORM\ManyToOne(inversedBy: 'medias')]
    private ?Club $associatedClub = null;

    #[ORM\Column]
    private bool $isArchived = false;

    public function __construct()
    {
        $this->associatedArticles = new ArrayCollection();
        $this->injuryArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSize(): int
    {
        $image_url = 'https://footballko.com/uploads/' . $this->filename;
        $image_path = realpath($image_url);
        if ($image_path !== false) {
            return filesize($image_path);
        } else {
            return 140000;
        }
    }


    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function setAltText(?string $altText): self
    {
        $this->altText = $altText;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getAssociatedArticles(): Collection
    {
        return $this->associatedArticles;
    }

    public function addAssociatedArticle(Article $associatedArticle): self
    {
        if (!$this->associatedArticles->contains($associatedArticle)) {
            $this->associatedArticles->add($associatedArticle);
            $associatedArticle->setMedia($this);
        }

        return $this;
    }

    public function removeAssociatedArticle(Article $associatedArticle): self
    {
        if ($this->associatedArticles->removeElement($associatedArticle)) {
            // set the owning side to null (unless already changed)
            if ($associatedArticle->getMedia() === $this) {
                $associatedArticle->setMedia(null);
            }
        }

        return $this;
    }

    public function getAssociatedClub(): ?Club
    {
        return $this->associatedClub;
    }

    public function setAssociatedClub(?Club $associatedClub): self
    {
        $this->associatedClub = $associatedClub;

        return $this;
    }

    public function __toString(): string
    {
        if($this->associatedClub == null){
            return $this->name;
        }

        else{
            return $this->associatedClub->getCityname()." - ".$this->name;
        }
    }

    /**
     * @return Collection<int, InjuryArticle>
     */
    public function getInjuryArticles(): Collection
    {
        return $this->injuryArticles;
    }

    public function addInjuryArticle(InjuryArticle $injuryArticle): self
    {
        if (!$this->injuryArticles->contains($injuryArticle)) {
            $this->injuryArticles->add($injuryArticle);
            $injuryArticle->setMedia($this);
        }

        return $this;
    }

    public function removeInjuryArticle(InjuryArticle $injuryArticle): self
    {
        if ($this->injuryArticles->removeElement($injuryArticle)) {
            // set the owning side to null (unless already changed)
            if ($injuryArticle->getMedia() === $this) {
                $injuryArticle->setMedia(null);
            }
        }

        return $this;
    }

    public function getAssociatedChampionship(): ?Championship
    {
        return $this->associatedChampionship;
    }

    public function setAssociatedChampionship(?Championship $associatedChampionship): self
    {
        $this->associatedChampionship = $associatedChampionship;

        return $this;
    }

    /**
     * @return bool
     */
    public function isArchived(): bool
    {
        return $this->isArchived;
    }

    /**
     * @param bool $isArchived
     */
    public function setIsArchived(bool $isArchived): void
    {
        $this->isArchived = $isArchived;
    }


}
