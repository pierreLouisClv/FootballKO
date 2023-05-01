<?php

namespace App\SEO ;

use Leogout\Bundle\SeoBundle\Seo\Twitter\TwitterSeoInterface;

class TwitterArticleResources implements TwitterSeoInterface {
    protected string $title;
    protected string $description;
    protected string $image;

    public function getSeoDescription(): string
    {
        return $this->description;
    }

    public function getSeoImage(): string
    {
        return $this->image;
    }

    public function getSeoTitle(): string
    {
        return $this->title;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }
}

