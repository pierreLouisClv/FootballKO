<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Championship;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,
                                public CategoryRepository $categoryRepository)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getLastArticles(int $limit = 15, Championship $champ = null): array
    {
        $date = (new \DateTime())->modify('+2 hours');
        $qb = $this->createQueryBuilder('a');
        if($champ != null){
            $qb->andWhere('a.mentioned_champ = :champ')
                ->setParameter(':champ', $champ);
        }
        return $qb
            ->andWhere('a.publishedAt <= :now')
            ->setParameter(':now', $date)
            ->orderBy('a.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getSurgeryArticles(int $limit = 15): array
    {
        $date = (new \DateTime())->modify('+2 hours');
        $qb = $this->createQueryBuilder('a');
        $categories = $this->categoryRepository->getSurgeryCategories();
        return $qb
            ->andWhere('a.publishedAt <= :now')
            ->setParameter(':now', $date)
            ->andWhere('a.category IN (:categories)')
            ->setParameter(':categories', $categories)
            ->orderBy('a.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getMercatoArticles(int $limit = 15):array
    {
        $date = (new \DateTime())->modify('+2 hours');
        $qb = $this->createQueryBuilder('a');
        $category = $this->categoryRepository->findOneBy(['slug' => 'mercato']);
        return $qb
            ->andWhere('a.publishedAt <= :now')
            ->setParameter(':now', $date)
            ->andWhere('a.category = :mercato')
            ->setParameter(':mercato', $category)
            ->orderBy('a.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
