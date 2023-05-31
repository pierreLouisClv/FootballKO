<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FooterController extends AbstractController {

    #[Route('/legal_mentions', name:'app_legal_mentions')]
    public function showLegalMentions():Response
    {
        return $this->render('legal_mentions/index.html.twig');
    }
}