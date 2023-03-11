<?php

namespace App\Controller;

use App\Entity\Banque;
use App\Form\BanqueType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class BanqueController extends AbstractController
{
    #[Route('/banque', name: 'app_banque')]
    public function index(ChartBuilderInterface $chartBuilder): Response
    {

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        return $this->render('banque/index.html.twig', [
            'page_name' => 'BanqueController',
            'chart' => $chart,
        ]);
    }

    #[Route('/banque/edit/{id<\d+>?0}', name: 'banque_edit')]
    public function addBanque(
        int $id,
        Request $request,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ): Response
    {
        $entityManager = $doctrine->getManager();
        $banque = $entityManager->getRepository(Banque::class)->find($id);
        if ($id == 0) {
            $banque = new Banque();
        }

        if (!$banque) {
            throw $this->createNotFoundException(
                'No banque found for id ' . $id
            );
        }

        $form = $this->createForm(BanqueType::class, $banque);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $banque = $form->getData();

            $errors = $validator->validate($banque);
            if (count($errors) > 0) {
                return new Response((string) $errors, 400);
            }

            $entityManager->persist($banque);
            $entityManager->flush();

            return $this->redirectToRoute('app_banque');
        }

        return $this->renderForm('banque/editBanque.html.twig', [
            'form' => $form,
            'page_name' => 'Type Operation',
        ]);
    }

    #[Route('/banque/delete/{id}', name: 'banque_delete')]
    public function deleteBanque(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $banque = $entityManager->getRepository(Banque::class)->find($id);

        if (!$banque) {
            throw $this->createNotFoundException(
                'No banque found for id ' . $id
            );
        }

        $entityManager->remove($banque);
        $entityManager->flush();

        return $this->redirectToRoute('app_banque');
    }

}