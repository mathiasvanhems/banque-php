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
    private $doctrine;
    private $em;
    private $emRepository;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine=$doctrine;
        $this->em = $this->doctrine->getManager();
        $this->emRepository=$this->em->getRepository(TypeOperation::class);
    }

    #[Route('/type', name: 'app_type_operation')]
    public function index(): Response
    {

        $types= $this->emRepository->findAllOrder();

        return $this->render('type_operation/index.html.twig', [
            'page_name' => 'Type Operation',
            'types'=>$types,
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
        $typeOperation = $this->emRepository->find($id);
        if ($id == 0) {
            $typeOperation = new TypeOperation();
            $typeOperation->setSortie(true);
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

            $this->emRepository->save($typeOperation,true);

            return $this->redirectToRoute('app_type_operation');
        }

        return $this->renderForm('type_operation/editTypeOperation.html.twig', [
            'form' => $form,
            'page_name' => 'Type Operation',
        ]);
    }

    #[Route('/type/delete/{id}', name: 'type_delete', methods: ["DELETE"])]
    public function deleteType(ManagerRegistry $doctrine, int $id): Response
    {

        $typeOperation = $this->emRepository->find($id);

        if (!$typeOperation) {
            throw $this->createNotFoundException(
                'No type found for id ' . $id
            );
        }

        $this->emRepository->remove($typeOperation,true);

        return $this->redirectToRoute('app_type_operation');
    }
}
