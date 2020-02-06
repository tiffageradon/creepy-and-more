<?php

namespace App\Repository;

use App\Entity\Videos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use PhpParser\Node\Expr\Array_;


/**
 * @method Videos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Videos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Videos[]    findAll()
 * @method Videos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideosRepository extends ServiceEntityRepository
{

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Videos::class);
        $this->paginator = $paginator;
    }
    

    /**
     * @return Videos
     */
// Affichage des 3 derniers résultats sans les archives
    public function findAllHome(){
        return $this->createQueryBuilder('a')
        ->andWhere('a.del = 0')
        ->orderBy("a.id", "DESC")
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();
    }

     /**
     * @return Videos
     */
// Affichage par pagination en ordre décroissant et par 5/page
    public function findByPaginate($page = 1): PaginationInterface
    {
        $query =  $this->createQueryBuilder('b')
        ->andWhere('b.del = 0')
        ->orderBy("b.id", "DESC")
        ->getQuery();

        return $this->paginator->paginate($query, $page, 5);
    }

    /**
     * @return Videos
     */
// Affichage par signalement et 5/page
    public function findByAdminSign($page = 1): PaginationInterface
    {
        $query =  $this->createQueryBuilder('c')
        ->andWhere('c.signalement = 1', 'c.del = 0')
        ->orderBy("c.id", "ASC")
        ->getQuery();

        return $this->paginator->paginate($query, $page, 5);
    }

     /**
     * @return Videos
     */
// Affichage des archives 5/page par ordre ID inversé
    public function findByArchive($page = 1): PaginationInterface
    {
        $query =  $this->createQueryBuilder('d')
        ->andWhere('d.del = 1')
        ->orderBy("d.id", "DESC")
        ->getQuery();

        return $this->paginator->paginate($query, $page, 5);
    }
// Recherche par mots dans le titre sans les archives
    public function searchVideoLike($titre) {
        return $this->createQueryBuilder('e')
            ->where('e.titre LIKE :titre')
            ->andWhere('e.del = 0')
            ->setParameter('titre', '%'.$titre.'%')
            ->getQuery()
            ->getResult();
    }
// Recherche par mots dans le titre des archives
    public function searchVideoArchives($titre) {
        return $this->createQueryBuilder('f')
            ->where('f.titre LIKE :titre')
            ->andWhere('f.del = 1')
            ->setParameter('titre', '%'.$titre.'%')
            ->getQuery()
            ->getResult();
    }



    // /**
    //  * @return Videos[] Returns an array of Videos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Videos
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
