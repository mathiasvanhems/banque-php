<?php

namespace App\Controller;

use App\Entity\TypeOperation;
use App\Form\TypeOperationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class TypeOperationController extends AbstractController
{
    #[Route('/type', name: 'app_type_operation')]
    public function index(): Response
    {
        return $this->render('type_operation/index.html.twig', [
            'controller_name' => 'TypeOperationController',
        ]);
    }

    #[Route('/type/edit/{id<\d+>?0}', name: 'type_edit')]
    public function addtype(
        int $id,
        Request $request,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ): Response
    {
        $entityManager = $doctrine->getManager();
        $typeOperation = $entityManager->getRepository(TypeOperation::class)->find($id);
        if ($id == 0) {
            $typeOperation = new TypeOperation();
        }

        if (!$typeOperation) {
            throw $this->createNotFoundException(
                'No type found for id ' . $id
            );
        }

        $form = $this->createForm(TypeOperationType::class, $typeOperation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $typeOperation = $form->getData();

            $errors = $validator->validate($typeOperation);
            if (count($errors) > 0) {
                return new Response((string) $errors, 400);
            }

            $entityManager->persist($typeOperation);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_operation');
        }

        return $this->renderForm('type_operation/editTypeOperation.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/type/delete/{id}', name: 'type_delete')]
    public function deleteType(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $typeOperation = $entityManager->getRepository(TypeOperation::class)->find($id);

        if (!$typeOperation) {
            throw $this->createNotFoundException(
                'No type found for id ' . $id
            );
        }

        $entityManager->remove($typeOperation);
        $entityManager->flush();

        return $this->redirectToRoute('app_type_operation');
    }
}
