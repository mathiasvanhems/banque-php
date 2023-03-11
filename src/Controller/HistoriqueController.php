<?php

namespace App\Controller;

use App\Entity\Historique;
use App\Form\HistoriqueType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HistoriqueController extends AbstractController
{
    #[Route('/historique/{annee<\d+>?0}', name: 'app_historique')]
    public function index(ChartBuilderInterface $chartBuilder, ManagerRegistry $doctrine, int $annee): Response
    {
        
        if (strlen((string)$annee)!=4){$annee=date('Y');}
        //dd($annee);
        $historiqueRepository=$doctrine->getManager()->getRepository(Historique::class);
        $historiques[] = $historiqueRepository->findAllFromYear($annee);
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $label = "Historique de l'année : ".$annee;
        $labels=[];
        $data= [];

        foreach($historiques[0] as &$value)
        {
            array_push($labels, $value->getPeriode()->format('d/m'));
            array_push($data, $value->getMontant());
        }

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $label,
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $data,
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


        return $this->render('historique/index.html.twig', [
            'page_name' => 'Historique',
            'chart' => $chart,
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
            $historique = new Historique();
            $historique->setPeriode(new \DateTime());
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
            'page_name' => 'Historique',
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
