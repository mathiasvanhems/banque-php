<?php

namespace App\Repository;

use App\Entity\Operation;
use App\Entity\TypeOperation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Operation>
 *
 * @method Operation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Operation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Operation[]    findAll()
 * @method Operation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operation::class);
    }

    public function save(Operation $entity, bool $flush = false)
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
        return $entity;
    }

    public function remove(Operation $entity, bool $flush = false): bool
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
        return true;
    }

   /**
    * @return Operation[] Returns an array of Operation objects
    */
   public function findAllWithType(): array
   {

    return $this
    ->createQueryBuilder('o')
    ->select('o.id', 'o.montant', 'o.detail', 'o.dateOperation', 't.libelle', 't.sortie', 't.recurrence')
    ->leftJoin('o.type', 't')
    ->addOrderBy('o.dateOperation','DESC')
    ->addOrderBy('t.sortie','ASC')
    ->addOrderBy('o.montant','DESC')
    ->getQuery()
    ->getResult();
   }

//    public function findOneBySomeField($value): ?Operation
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
