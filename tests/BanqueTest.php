<?php

namespace App\Tests;

use App\Entity\Banque;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BanqueTest extends KernelTestCase
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
    
    public function testBanque(): void
    {
        
        /* Create entity Banque */
        $banque = new Banque();
        $banque->setCompteCourant(1234.56);
        $banque->setLivretA(1234.56);
        $banque->setEpargne(1234.56);
        $banque->setTicketRestaurant(1234.56);
        $this->assertSame('1234.56',$banque->getCompteCourant());
        $this->assertSame('1234.56',$banque->getLivretA());
        $this->assertSame('1234.56',$banque->getEpargne());
        $this->assertSame('1234.56',$banque->getTicketRestaurant());

        /* test create*/
        $emRepository=$this->entityManager->getRepository(Banque::class);
        $this->assertIsObject($emRepository->save($banque));
        
        /* test find */ 
        $banque_test=$emRepository->find($banque->getId());
        $this->assertIsObject($banque_test);
        
        /* test remove */ 
        $this->assertTrue($emRepository->remove($banque));
        unset($banque_test);

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
