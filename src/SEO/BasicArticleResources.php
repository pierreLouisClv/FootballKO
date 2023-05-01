<?php

namespace App\SEO;
use Doctrine\Common\Collections\ArrayCollection;
use Leogout\Bundle\SeoBundle\Seo\Basic\BasicSeoInterface;

class BasicArticleResources implements BasicSeoInterface {
    protected $name;
    protected $description;
    protected $tags = [];

    public function getSeoDescription(): string
    {
        return $this->name;
    }

    public function getSeoKeywords(): string
    {
        return implode(',', $this->tags);
    }

    public function getSeoTitle(): string
    {
        return $this->name;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    public function addKeyword(string $tag):void
    {
        $keywords = new ArrayCollection($this->tags);
        $keywords->add($tag);
        $this->tags = $keywords->toArray();
    }
}