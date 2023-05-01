<?php

namespace App\Repository;

use App\Entity\Championship;
use App\Entity\InjuryArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InjuryArticle>
 *
 * @method InjuryArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method InjuryArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method InjuryArticle[]    findAll()
 * @method InjuryArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InjuryArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InjuryArticle::class);
    }

    public function save(InjuryArticle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InjuryArticle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findCurrentArticle(Championship $championship, $day) : InjuryArticle{
        $article = $this->findOneBy(['championship' => $championship, 'day'=>$day]);
        return $article;
    }

    public function getLastInjuryArticles(ArrayCollection $champs): ArrayCollection{
        $injuryArticles = new ArrayCollection();
        foreach($champs as $champ){
            $currentChampDay = $champ->getCurrentDay();
            $injuryArticle = $this->findOneBy(['championship' => $champ, 'day'=>$currentChampDay]);
            if($injuryArticle == null){
                $injuryArticle = $this->findOneBy(['championship' => $champ, 'day'=>$currentChampDay - 1]);
            }
            $injuryArticles->add($injuryArticle);
        }
        return $injuryArticles;
    }

//    /**
//     * @return InjuryArticle[] Returns an array of InjuryArticle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InjuryArticle
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
