<?php

namespace App\Repository;

use App\Entity\Textes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use PhpParser\Node\Expr\Array_;

/**
 * @method Textes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Textes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Textes[]    findAll()
 * @method Textes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextesRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Textes::class);
        $this->paginator = $paginator;
    }

    /**
     * @return Textes
     */
// Recherche pour tous les textes par ordre décroissant d'id et non archivés, 3 résultats max
    public function findAllHome(){
        return $this->createQueryBuilder('a')
        ->andWhere('a.del = 0')
        ->orderBy("a.id", "DESC")
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();
    }
    
    /**
     * @return Textes
     */
// Pagination des résultats 
    public function findByPaginate($page = 1): PaginationInterface
    {
        $query =  $this->createQueryBuilder('b')
        ->andWhere('b.del = 0')
        ->orderBy("b.id", "DESC")
        ->getQuery();

        return $this->paginator->paginate($query, $page, 5);
    }

    /**
     * @return Textes
     */
// recherche par signalement sans les archives
    public function findByAdminSign($page = 1): PaginationInterface
    {
        $query =  $this->createQueryBuilder('c')
        ->andWhere('c.signalement = 1', 'c.del = 0')
        ->orderBy("c.id", "ASC")
        ->getQuery();

        return $this->paginator->paginate($query, $page, 5);
    }

     /**
     * @return Textes
     */
// Affichage par archive
    public function findByArchive($page = 1): PaginationInterface
    {
        $query =  $this->createQueryBuilder('d')
        ->andWhere('d.del = 1')
        ->orderBy("d.id", "ASC")
        ->getQuery();

        return $this->paginator->paginate($query, $page, 5);
    }

    
// Option de recherche par mot contenu dans le titre sans les archives
    public function searchTextLike($titre) {
        return $this->createQueryBuilder('e')
            ->where('e.titre LIKE :titre')
            ->andWhere('e.del = 0')
            ->setParameter('titre', '%'.$titre.'%')
            ->getQuery()
            ->getResult();
    }
// Option de recherche par mot contenu dans le titre pour les archives
    public function searchTextArchives($titre) {
        return $this->createQueryBuilder('f')
            ->where('f.titre LIKE :titre')
            ->andWhere('f.del = 1')
            ->setParameter('titre', '%'.$titre.'%')
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return Textes[] Returns an array of Textes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    } 
    */

    /*
    public function findOneBySomeField($value): ?Textes
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
