<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Championship;
use App\Entity\Club;
use App\Entity\InjuryArticle;
use App\Entity\Media;
use App\Entity\Player;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ){

    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $user = $this->getUser();
        if($user == null || in_array('ROLE_ADMIN', $this->getUser()->getRoles())){
            $url = $this->adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl();
            return $this->redirect($url);
        }

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('FootballKO Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToCrud('Articles', 'fa fa-sport', Article::class);

        yield MenuItem::linkToCrud('Articles tableau', 'fa fa-sport', InjuryArticle::class);

        yield MenuItem::linkToCrud('Images', 'fa fa-sport', Media::class);

        yield MenuItem::linkToCrud('Catégories', 'fa fa-sport', Category::class);

        yield MenuItem::linkToCrud('Clubs', 'fa fa-sport', Club::class);

        yield MenuItem::linkToCrud('Joueurs', 'fa fa-sport', Player::class);

        yield MenuItem::linkToCrud('Championnats', 'fa fa-sport', Championship::class);


    }

}
