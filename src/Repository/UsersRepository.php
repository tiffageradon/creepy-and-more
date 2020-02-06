<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use PhpParser\Node\Expr\Array_;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
     /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Users::class);
        $this->paginator = $paginator;
    }

     /**
     * @return Users
     */
// Pagination des utilisateurs par 10/page et par username dans l'ordre alphabétique
    public function findByPaginate($page = 1): PaginationInterface
    {
        $query =  $this->createQueryBuilder('b')
        ->orderBy("b.username", "ASC")
        ->getQuery();

        return $this->paginator->paginate($query, $page, 10);
    }
// recherche par username avec mots contenus dans le nom
    public function searchUserLike($username) {
        return $this->createQueryBuilder('c')
            ->where('c.username LIKE :username')
            ->setParameter('username', '%'.$username.'%')
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Users[] Returns an array of Users objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
