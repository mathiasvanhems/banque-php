<?php

namespace App\Repository;

use App\Entity\TypeOperation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeOperation>
 *
 * @method TypeOperation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeOperation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeOperation[]    findAll()
 * @method TypeOperation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeOperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeOperation::class);
    }

    public function save(TypeOperation $entity, bool $flush = false)
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
        return $entity;
    }

    public function remove(TypeOperation $entity, bool $flush = false):bool
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
        return true;
    }

   /**
    * @return TypeOperation[] Returns an array of TypeOperation objects
    */
   public function findAllOrder(): array
   {
       return $this->createQueryBuilder('t')
           ->AddOrderBy('t.sortie', 'ASC')
           ->AddOrderBy('t.recurrence', 'ASC')
           ->AddOrderBy('t.libelle', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?TypeOperation
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
