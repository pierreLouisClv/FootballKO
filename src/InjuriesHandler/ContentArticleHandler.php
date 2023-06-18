<?php

namespace App\InjuriesHandler;

use App\Entity\Article;
use App\Repository\ChampionshipRepository;
use App\Repository\ClubRepository;

class ContentArticleHandler
{

    public function __construct(
        public ClubRepository $clubRepository
    )
    {
    }

    public function handleContent(Article $article):void
    {
        $content = $article->getContent();

        $content = $this->replaceULTags($content);
        $content = $this->addLineBreakAfterClosingParagraph($content);
        $article->setContent($content);
        $this->identifyClubs($article);
    }

    public function convertToBoostsrap(string $content):string
    {

        return $content;
    }

    public function identifyClubs(Article $article):void
    {
        $content = $article->getContent();

        $result = $this->replaceSlugs($content);
        $slugs = $result['slugs'];
        $content = $result['content'];
        foreach ($slugs as $slug)
        {
            $club = $this->clubRepository->findOneBy(['slug'=>$slug]);
            if($club != null)
            {
                $article->addClub($club);
            }
        }

        $article->setContent($content);
    }

    function replaceSlugs($content) {
        $pattern = '/##([^#]+)##/'; // Recherche les slugs entre ##

        $slugs = [];

        $content = preg_replace_callback($pattern, function($matches) use (&$slugs) {
            $slug = $matches[1];
            $slugs[] = $slug;

            $club = $this->clubRepository->findOneBy(['slug' => $slug]);

            // Vérifier si la valeur a été trouvée
            if ($club) {
                $replacementSlug = '<a href="{{ path(\'app_club\', \'slug\' : \'' . $slug . '\') }}">' . $club->getClubName() . '</a>';
            } else {
                // Cas où la valeur n'a pas été trouvée
                $replacementSlug = '<a href="{{ path(\'app_club\', \'slug\' : \'' . $slug . '\') }}">Slug introuvable</a>';
            }

            return $replacementSlug;
        }, $content);

        return [
            'content' => $content,
            'slugs' => $slugs
        ];
    }

    public function addLineBreakAfterClosingParagraph($content):string {
        $pattern = '/<\/p>/';

        $content = preg_replace($pattern, '</p><br>', $content);

        return $content;
    }

    function replaceULTags($content):string {
        $search = '<ul>';
        $replace = '<ul class="ml-4" style="list-style-type: disc;">';

        $content = str_replace($search, $replace, $content);

        return $content;
    }
    
}