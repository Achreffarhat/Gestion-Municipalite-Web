<?php

namespace App\Repository;

use App\Entity\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicule>
 *
 * @method Vehicule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicule[]    findAll()
 * @method Vehicule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehiculeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicule::class);
    }

    public function save(Vehicule $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Vehicule $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Vehicule[] Returns an array of Vehicule objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Vehicule
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
 /**
     * return Vehicule[]
     */

     public function SortBydisponible(){
        return $this->createQueryBuilder('e')
            ->orderBy('e.disponible','ASC')
            ->getQuery()
            ->getResult()
            ;
    }




    public function SortBymarque(){
        return $this->createQueryBuilder('e')
            ->orderBy('e.marque','ASC')
            ->getQuery()
            ->getResult()
            ;
    }
     
    public function findBydisponible( $disponible)
{
    return $this-> createQueryBuilder('e')
        ->andWhere('e.disponible LIKE :nom')
        ->setParameter('nom','%' .$disponible. '%')
        ->getQuery()
        ->execute();
}


public function findbymarque($marque)
{
    return $this->createQueryBuilder('e')
        ->where('e.marque LIKE :marque')
        ->setParameter('marque', '%'.$marque.'%')
        ->getQuery()
        ->getResult();
}

}