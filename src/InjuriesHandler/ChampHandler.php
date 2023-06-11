<?php

namespace App\InjuriesHandler;

use App\Repository\ChampionshipRepository;

class ChampHandler{
    public function __construct(public ChampionshipRepository $championshipRepository)
    {
    }


    public function getClubsFromPrevSeason(array $relegatedClubs):array
    {

    }
}