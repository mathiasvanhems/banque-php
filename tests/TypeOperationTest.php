<?php

namespace App\Tests;

use App\Entity\TypeOperation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TypeOperationTest extends KernelTestCase
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
        /* Create entity TypeOperation */
        $typeOperation = new TypeOperation();
        $typeOperation->setLibelle('test');
        $typeOperation->setRecurrence('NA');
        $typeOperation->setSortie(true);
        $this->assertSame('test',$typeOperation->getLibelle());
        $this->assertSame('NA',$typeOperation->getRecurrence());
        $this->assertTrue($typeOperation->isSortie());

        /* test create*/
        $emRepository=$this->entityManager->getRepository(TypeOperation::class);
        $this->assertIsObject($emRepository->save($typeOperation));
        
        /* test find */ 
        $typeOperation_test=$emRepository->find($typeOperation->getId());
        $this->assertIsObject($typeOperation_test);
        
        /* test remove */ 
        $this->assertTrue($emRepository->remove($typeOperation));
        unset($typeOperation_test);

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
