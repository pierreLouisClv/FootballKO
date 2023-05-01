<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Championship;
use App\Entity\Club;
use App\Entity\InjuryTab;
use App\Entity\Player;
use App\Repository\ClubRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $Ligue1 = new Championship('Ligue 1');
        $PL = new Championship('Premier League');
        $SerieA = new Championship('Série A');
        $Liga = new Championship('LaLiga');

        $champs = array($Liga, $PL, $SerieA, $Ligue1);

        foreach ($champs as $champ){
            $manager->persist($champ);
        }

        $paris = new Club("Paris", $Ligue1);
        $paris->setClubName("Paris Saint-Germain");
        $marseille = new Club("Marseille", $Ligue1);
        $marseille->setClubName("Olympique de Marseille");
        $lille = new Club("Lille", $Ligue1);
        $lille->setClubName("Lille OSC");
        $monaco = new Club("Monaco",$Ligue1);
        $monaco->setClubName("AS Monaco");
        $lyon = new Club("Lyon",$Ligue1);
        $lyon->setClubName("Olympique Lyonnais");
        $nice = new Club("Nice",$Ligue1);
        $nice->setClubName("OGC Nice");
        $lens = new Club("Lens",$Ligue1);
        $lens->setClubName("RC Lens");
        $reims = new Club("Reims",$Ligue1);
        $reims->setClubName("Stade de Reims");
        $lorient = new Club("Lorient",$Ligue1);
        $lorient->setClubName("FC Lorient");
        $brest = new Club("Brest",$Ligue1);
        $brest->setClubName("Stade Brestois");
        $clermont = new Club("Clermont",$Ligue1);
        $clermont->setClubName("Clermont Foot");
        $nantes = new Club("Nantes",$Ligue1);
        $nantes->setClubName("FC Nantes");
        $strasbourg = new Club("Strasbourg",$Ligue1);
        $strasbourg->setClubName("RC Strasbourg");
        $toulouse = new Club("Toulouse",$Ligue1);
        $toulouse->setClubName("Toulouse FC");
        $angers = new Club("Angers",$Ligue1);
        $angers->setClubName("Angers SCO");
        $ajaccio = new Club("Ajaccio",$Ligue1);
        $ajaccio->setClubName("AC Ajaccio");
        $troyes = new Club("Troyes",$Ligue1);
        $troyes->setClubName("ESTAC Troyes");
        $auxerre = new Club("Auxerre",$Ligue1);
        $auxerre->setClubName("AJ Auxerre");
        $rennes = new Club("Rennes",$Ligue1);
        $rennes->setClubName("Stade Rennais");
        $montpellier = new Club("Montpellier",$Ligue1);
        $montpellier->setClubName("Montpellier HSC");

        $clubs = array($paris,$montpellier,$rennes,$auxerre,$lens,$lille,$lyon,$lorient,$troyes,$toulouse,$monaco,$marseille,$ajaccio,$angers,$nantes,$clermont,$strasbourg,$brest,$nice,$reims);

        foreach($clubs as $club){
            $injuryTab = new InjuryTab($champ->getCurrentDay(), $club);
            $manager->persist($injuryTab);
            $manager->persist($club);
        }

        $chevalier = new Player('Lucas', 'Chevalier', 'GDB', $lille);
        $costil = new Player('Benoit', 'Costil', 'GDB', $lille);
        $fonte = new Player('José', 'Fonte', 'DC', $lille);
        $djalo = new Player('Tiago', 'Djalo', 'DC', $lille);
        $yoro = new Player('Leny', 'Yoro', 'DC', $lille);
        $alexsandro = new Player(null,'Alexsandro', 'DC', $lille);
        $diakite = new Player('Bafodé','Diakité', 'DC', $lille);
        $gudmunsson = new Player('Gabriel','Gudmunsson', 'DL', $lille);
        $ismaily = new Player(null,'Ismaily', 'DL', $lille);
        $andre = new Player('Benjamin','André', 'MD', $lille);
        $gomes = new Player('André','Gomes', 'MD', $lille);
        $martin = new Player('Jonas','Martin', 'MD', $lille);
        $baleba = new Player('Carlos','Baleba', 'MD', $lille);
        $angel = new Player('Angel','Gomes', 'MO', $lille);
        $cabella = new Player('Rémy','Cabella', 'MO', $lille);
        $bamba = new Player('Jonathan','Bamba', 'MO', $lille);
        $ounas = new Player('Adam','Ounas', 'MO', $lille);
        $zhegrova = new Player('Edon','Zhegrova', 'ATT', $lille);
        $david = new Player('Jonathan','David', 'ATT', $lille);
        $bayo = new Player('Mohamed','Bayo', 'ATT', $lille);
        $virginius = new Player('Alan','Virginius', 'ATT', $lille);
        $weah = new Player('Timothy','Weah', 'ATT', $lille);

        $playerslille = array($weah,$chevalier,$costil,$martin, $baleba,$ounas,$zhegrova,$david,
            $bayo,$virginius,$bayo,$cabella,$bamba,$angel,$gomes,
            $andre,$ismaily,$gudmunsson,$alexsandro,$diakite,$yoro,$fonte,$djalo);

        foreach($playerslille as $player){
            $manager->persist($player);
        }

        $manager->flush();

        $catAbsents = new Category("Absents");
        $catGroupe = new Category("Groupe");
        $catTableau = new Category("Tableau");
        $catCommission = new Category("Commission de discipline");
        $catConf = new Category("Conférence de presse");
        $catZoom = new Category("Zoom");

        $arrayCat = array($catCommission, $catAbsents, $catTableau, $catGroupe, $catConf, $catZoom);

        foreach($arrayCat as $category) {
            $manager->persist($category);
        }

        foreach($clubs as $club){
            $manager->persist(new InjuryTab($club->getChampionship()->getCurrentDay(), $club));
        }

        $manager->flush();


    }
}
