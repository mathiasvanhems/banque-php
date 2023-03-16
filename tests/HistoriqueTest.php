<?php

namespace App\Tests;

use App\Entity\Historique;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HistoriqueTest extends KernelTestCase
{

/**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
    
    public function testMain(): void
    {

        /* Create entity */
        $date=new \DateTime;
        $entity = new Historique();
        $entity->setMontant(1234.56);
        $entity->setPeriode($date);
        $this->assertSame('1234.56',$entity->getMontant());
        $this->assertSame($date,$entity->getPeriode());
;
        /* test create*/
        $emRepository=$this->entityManager->getRepository(Historique::class);
        $this->assertIsObject($emRepository->save($entity));
        
        /* test find */ 
        $entity_test=$emRepository->find($entity->getId());
        $this->assertIsObject($entity_test);
        
        /* test remove */ 
        $this->assertTrue($emRepository->remove($entity));
        unset($entity_test);

        /* suppression des seq créées */
        $RAW_QUERY = 'CALL fix_seq();'; 
        $statement = $this->entityManager->getConnection()->prepare($RAW_QUERY);
        $statement->executeQuery();
        $this->assertTrue(true);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
