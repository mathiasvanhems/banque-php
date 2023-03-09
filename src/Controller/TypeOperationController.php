<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeOperationController extends AbstractController
{
    #[Route('/type/operation', name: 'app_type_operation')]
    public function index(): Response
    {
        return $this->render('type_operation/index.html.twig', [
            'controller_name' => 'TypeOperationController',
        ]);
    }
}
