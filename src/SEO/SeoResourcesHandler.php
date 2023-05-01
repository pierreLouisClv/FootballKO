<?php

namespace App\SEO ;
use App\Entity\Article;
use App\Entity\InjuryArticle;
use phpDocumentor\Reflection\Types\Array_;

class SeoResourcesHandler{

    public function createBasicResourcesForArticle(Article $article):BasicArticleResources
    {
        $myResources = new BasicArticleResources();
        $myResources->setName($article->getTitle());
        $myResources->setDescription($article->getIntroduction());
        $myResources->setTags($this->getKeywordsToString($article));

        return $myResources;
    }

    public function createBasicResourcesForInjuryArticle(InjuryArticle $article):BasicArticleResources
    {
        $myResources = new BasicArticleResources();
        $myResources->setName($article->getTitle());
        $myResources->setDescription($article->getIntroduction());
        $tags = array($article->getChampionship()->getChampName(), "blessures", "absents", "Journée ".$article->getChampionship()->getCurrentDay(), "tableau des absents" );
        $myResources->setTags($tags);

        return $myResources;
    }

    public function getKeywordsToString(Article $article):array
    {
        $spacedKeywords = $article->getKeywords();
        return explode(";", $spacedKeywords);
    }

    public function createTwitterResourcesArticle(Article $article):TwitterArticleResources
    {
        $myResources = new TwitterArticleResources();
        $myResources->setImage("https://footballko.com/uploads/".$article->getMedia()->getFilename());
        $myResources->setDescription($article->getIntroduction());
        $myResources->setTitle($article->getTitle());

        return $myResources;
    }

    public function createTwitterResourcesForInjuryArticle(InjuryArticle $article):TwitterArticleResources
    {
        $myResources = new TwitterArticleResources();
        $myResources->setImage("https://footballko.com/uploads/".$article->getMedia()->getFilename());
        $myResources->setDescription($article->getIntroduction());
        $myResources->setTitle($article->getTitle());

        return $myResources;
    }

    public function createOgResourcesArticle(Article $article):OgArticleResources
    {
        $myResources = new OgArticleResources();
        $myResources->setImage("https://footballko.com/uploads/".$article->getMedia()->getFilename());
        $myResources->setDescription($article->getIntroduction());
        $myResources->setTitle($article->getTitle());

        return $myResources;

    }

    public function createOgResourcesForInjuryArticle(InjuryArticle $article):OgArticleResources
    {
        $myResources = new OgArticleResources();
        $myResources->setImage("https://footballko.com/uploads/".$article->getMedia()->getFilename());
        $myResources->setDescription($article->getIntroduction());
        $myResources->setTitle($article->getTitle());

        return $myResources;

    }
}