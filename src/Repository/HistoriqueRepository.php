<?php

namespace App\Repository;

use App\Entity\Historique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Historique>
 *
 * @method Historique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Historique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Historique[]    findAll()
 * @method Historique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoriqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Historique::class);
    }

    public function save(Historique $entity, bool $flush = false)
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
        return $entity;
    }

    public function remove(Historique $entity, bool $flush = false): bool
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
        return true;
    }

    /**
     * @return Historique[] Returns an array of Historique objects
     */
    public function findAllFromYear($value): array
    {
        $periodeStart= $value.'-01-01';
        $periodeEnd=$value.'-12-31';
        return $this->createQueryBuilder('h')
            ->andWhere('h.periode >= :start and h.periode <= :end ')
            ->setParameter('start', $periodeStart)
            ->setParameter('end', $periodeEnd)
            ->orderBy('h.periode', 'ASC')
            ->getQuery()
            ->getResult()
        ;
}

//    public function findOneBySomeField($value): ?Historique
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
