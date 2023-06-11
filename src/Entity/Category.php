<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Gedmo\Slug(fields:["name"])]
    #[ORM\Column(length: 128, unique: true)]
    private $slug;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Article::class)]
    private Collection $articles;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: InjuryArticle::class)]
    private Collection $injuryArticles;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shortName = null;

    public function __construct($name = null)
    {
        if($name != null)
        {
            $this->name = $name;
        }
        $this->articles = new ArrayCollection();
        $this->injuryArticles = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }



    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
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
            $injuryArticle->setCategory($this);
        }

        return $this;
    }

    public function removeInjuryArticle(InjuryArticle $injuryArticle): self
    {
        if ($this->injuryArticles->removeElement($injuryArticle)) {
            // set the owning side to null (unless already changed)
            if ($injuryArticle->getCategory() === $this) {
                $injuryArticle->setCategory(null);
            }
        }

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(?string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }
}
