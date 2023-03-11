<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Form\OperationType;
use App\Entity\TypeOperation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OperationController extends AbstractController
{
    private $doctrine;
    private $em;
    private $emRepository;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine=$doctrine;
        $this->em = $this->doctrine->getManager();
        $this->emRepository=$this->em->getRepository(Operation::class);
    }

    #[Route('/operation', name: 'app_operation')]
    public function index(): Response
    {

        $operations= $this->emRepository->findAllWithType();
        return $this->render('operation/index.html.twig', [
            'page_name' => 'Operation',
            'operations'=> $operations,
        ]);
    }

    #[Route('/operation/edit/{id<\d+>?0}', name: 'operation_edit')]
    public function addOperation(
        int $id,
        Request $request,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ): Response
    {
        $operation=$this->emRepository->find($id);
        if ($id == 0) {
            $operation = new Operation();
            $operation->setDateOperation(new \DateTime());
        }

        if (!$operation) {
            throw $this->createNotFoundException(
                'No operation found for id ' . $id
            );
        }

        $form = $this->createForm(OperationType::class, $operation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $operation = $form->getData();

            $errors = $validator->validate($operation);
            if (count($errors) > 0) {
                return new Response((string) $errors, 400);
            }
            $this->emRepository->save($operation, true);

            return $this->redirectToRoute('app_operation');
        }

        return $this->renderForm('operation/editOperation.html.twig', [
            'form' => $form,
            'page_name' => 'Operation',
        ]);
    }

    #[Route('/operation/delete/{id}', name: 'operation_delete')]
    public function deleteOperation(ManagerRegistry $doctrine, int $id): Response
    {
        $operation=$this->emRepository->find($id);
        if (!$operation) {
            throw $this->createNotFoundException(
                'No operation found for id ' . $id
            );
        }
        try {
            $this->emRepository->remove($operation, true);
        } catch (\Exception $e) {
            echo "Exception Found - " . $e->getMessage() . "<br/>";
        }

        return $this->redirectToRoute('app_operation');
    }
}
