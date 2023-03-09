<?php

namespace App\Controller;

use App\Entity\Banque;
use App\Form\BanqueType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BanqueController extends AbstractController
{
    #[Route('/banque', name: 'app_banque')]
    public function index(): Response
    {
        return $this->render('banque/index.html.twig', [
            'controller_name' => 'BanqueController',
        ]);
    }

    #[Route('/addBanque', name: 'add_banque')]
    public function addBanque(Request $request,ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        // just set up a fresh $task object (remove the example data)
        $banque = new Banque();

        $form = $this->createForm(BanqueType::class, $banque);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $banque = $form->getData();



            
            $entityManager->persist($banque);
            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('app_banque');
        }

        return $this->renderForm('banque/addBanque.html.twig', [
            'form' => $form,
        ]);
    }

}
