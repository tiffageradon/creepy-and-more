<?php

namespace App\Repository;

use App\Entity\Commentaires;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use PhpParser\Node\Expr\Array_;

/**
 * @method Commentaires|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaires|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaires[]    findAll()
 * @method Commentaires[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentairesRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Commentaires::class);
        $this->paginator = $paginator;
    }

     /**
     * @return Commentaires
     */
// Recherche par signalement 
    public function findByAdminSign($page = 1): PaginationInterface
    {
        $query =  $this->createQueryBuilder('c')
        ->andWhere('c.signalement = 1', 'c.del = 0')
        ->orderBy("c.id", "DESC")
        ->getQuery();

        return $this->paginator->paginate($query, $page, 5);
    }

     /**
     * @return Commentaires
     */
// Recherche par archives
    public function findByArchive($page = 1): PaginationInterface
    {
        $query =  $this->createQueryBuilder('d')
        ->andWhere('d.del = 1')
        ->orderBy("d.id", "DESC")
        ->getQuery();

        return $this->paginator->paginate($query, $page, 5);
    }


    // /**
    //  * @return Commentaires[] Returns an array of Commentaires objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Commentaires
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
