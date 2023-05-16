<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    public function save(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function findLast()
    {
    return $this->createQueryBuilder('p')
        ->select('p', 'c')
        ->join('p.typee', 'c')
        ->getQuery()
        ->getResult()
    ;}


//    /**
//     * @return Reclamation[] Returns an array of Reclamation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reclamation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function barDep(){
    return $this->createQueryBuilder('r')
    ->select('count(r.id)')
    ->leftJoin('r.type', 't')
    ->where('t.type LIKE :reclamation')
    // ->where('r.typee LIKE : reclamation')
    ->setParameter('reclamation','Collecte des déchets')
    ->getQuery()
    ->getSingleScalarResult();
}

public function barArr(){
    return $this->createQueryBuilder('r')
    ->select('count(r.id)')
    ->leftJoin('r.type', 't')
     ->where('t.type LIKE :reclamation')
    // ->where('r.typee LIKE :  reclamation')
    ->setParameter('reclamation','Éclairage public')
    ->getQuery()
    ->getSingleScalarResult();
}



public function findInput($value)
    {
        return $this->createQueryBuilder('r')
            ->Where('r.nom LIKE :nom')
            ->setParameter('nom', "%".$value."%")
            ->getQuery()
            ->getResult()
            ;
    }


  

    public function SortBynom(){
        return $this->createQueryBuilder('e')
            ->orderBy('e.nom','ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function SortBydate(){
        return $this->createQueryBuilder('e')
            ->orderBy('e.date_reclamation','ASC')
            ->getQuery()
            ->getResult()
            ;
    }
     
    public function findBynom( $nom)
{
    return $this-> createQueryBuilder('e')
        ->andWhere('e.nom LIKE :nom')
        ->setParameter('nom','%' .$nom. '%')
        ->getQuery()
        ->execute();
}

public function findByprenom( $prenom)
{
    return $this-> createQueryBuilder('e')
        ->andWhere('e.prenom LIKE :prenom')
        ->setParameter('prenom','%' .$prenom. '%')
        ->getQuery()
        ->execute();
}
   



    

    


}
