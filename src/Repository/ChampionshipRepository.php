<?php

namespace App\Repository;

use App\Entity\Championship;
use App\Entity\Club;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Championship>
 *
 * @method Championship|null find($id, $lockMode = null, $lockVersion = null)
 * @method Championship|null findOneBy(array $criteria, array $orderBy = null)
 * @method Championship[]    findAll()
 * @method Championship[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChampionshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,
                                public EntityManagerInterface $em
    )
    {
        parent::__construct($registry, Championship::class);
    }

    public function save(Championship $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Championship $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function setNextDay(Championship $champ):void{
        $prevDay = $champ->getCurrentDay();
        $champ->setCurrentDay($prevDay + 1);
        $this->em->flush();
    }

    public function findChamps():ArrayCollection
    {
        $champs = new ArrayCollection();
        $ligue1 = $this->findOneBy(['slug' => 'ligue-1']);
        $premierleague = $this->findOneBy(['slug' => 'premier-league']);
        $seriea = $this->findOneBy(['slug' => 'serie-a']);
        $liga = $this->findOneBy(['slug' => 'laliga']);

        $champs->add($ligue1);
        $champs->add($premierleague);
        $champs->add($seriea);
        $champs->add($liga);

        return $champs;
    }
}
