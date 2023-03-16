<?php

namespace App\Tests;

use App\Entity\Operation;
use App\Entity\TypeOperation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OperationTest extends KernelTestCase
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
        
        /* Create entity Operation */
        $emRepositoryType=$this->entityManager->getRepository(TypeOperation::class);
        $operation = new Operation();
        $date=new \DateTime;
        $typeOperation = new TypeOperation();
        $typeOperation->setLibelle('test');
        $typeOperation->setRecurrence('NA');
        $typeOperation->setSortie(true);

        $emRepositoryType->save($typeOperation);
        

        $operation->setDateOperation($date);
        $operation->setDetail('test');
        $operation->setMontant(1234.56);

        $operation->setType($emRepositoryType->find($typeOperation->getId()));
        $this->assertSame($date,$operation->getDateOperation());
        $this->assertSame('test',$operation->getDetail());
        $this->assertSame('1234.56',$operation->getMontant());
        $this->assertIsObject($operation->getType());

         /* test create*/
        $emRepository=$this->entityManager->getRepository(Operation::class);
        $this->assertIsObject($emRepository->save($operation));
        
        /* test find */ 
        $operation_test=$emRepository->find($operation->getId());
        $this->assertIsObject($operation_test);
        
        /* test remove */ 
        $this->assertTrue($emRepository->remove($operation));
        unset($operation_test);

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
