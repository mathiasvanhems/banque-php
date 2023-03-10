<?php

namespace App\Controller;

use App\Entity\Historique;
use App\Form\HistoriqueType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HistoriqueController extends AbstractController
{
    #[Route('/historique', name: 'app_historique')]
    public function index(): Response
    {
        return $this->render('historique/index.html.twig', [
            'controller_name' => 'HistoriqueController',
        ]);
    }


    #[Route('/historique/edit/{id<\d+>?0}', name: 'historique_edit')]
    public function addhistorique(
        int $id,
        Request $request,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ): Response
    {
        setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
        $entityManager = $doctrine->getManager();
        $historique = $entityManager->getRepository(Historique::class)->find($id);
        if ($id == 0) {
            //dd(strftime(" in French %A le %d %B, %Y"));
            $historique = new Historique();
            $historique->setMois(ucfirst(strftime("%B")));
            $historique->setAnnee(date("Y"));
        }

        if (!$historique) {
            throw $this->createNotFoundException(
                'No type found for id ' . $id
            );
        }

        $form = $this->createForm(HistoriqueType::class, $historique);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $historique = $form->getData();

            $errors = $validator->validate($historique);
            if (count($errors) > 0) {
                return new Response((string) $errors, 400);
            }

            $entityManager->persist($historique);
            $entityManager->flush();

            return $this->redirectToRoute('app_historique');
        }

        return $this->renderForm('historique/editHistorique.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/historique/delete/{id}', name: 'historique_delete')]
    public function deleteType(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $historique = $entityManager->getRepository(Historique::class)->find($id);

        if (!$historique) {
            throw $this->createNotFoundException(
                'No type found for id ' . $id
            );
        }

        $entityManager->remove($historique);
        $entityManager->flush();

        return $this->redirectToRoute('app_historique');
    }

}
