<?php

namespace App\Controller;

use App\Entity\Banque;
use App\Entity\Historique;
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
    private $doctrine;
    private $em;
    private $emRepository;
    private $emRepositoryHistorique;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine=$doctrine;
        $this->em = $this->doctrine->getManager();
        $this->emRepository=$this->em->getRepository(Banque::class);
        $this->emRepositoryHistorique=$this->em->getRepository(Historique::class);

    }
    #[Route('/banque', name: 'app_banque')]
    public function index(ChartBuilderInterface $chartBuilder): Response
    {
        $annee=date('Y');
        $taux=2;

        /* recup data */
        $banques = $this->emRepository->findAll();
        $historiques = $this->emRepositoryHistorique->findAllFromYear($annee);
        

        /* Calcul */
        $total=floatval($banques[0]->getCompteCourant())+floatval($banques[0]->getLivretA())+floatval($banques[0]->getEpargne())+floatval($banques[0]->getTicketRestaurant());
        $livretABonus=floatval($banques[0]->getLivretA())*($taux)/100;

        /* Charts */
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $label = "Historique de l'année : ".$annee;
        $labels=[];
        $data= [];

        foreach($historiques as &$value)
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



        return $this->render('banque/index.html.twig', [
            'page_name' => 'Banque',
            'chart' => $chart,
            'banques'=>$banques,
            'total' => $total,
            'livretA'=>$livretABonus,
            'taux'=>$taux,
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
        $banque = $this->emRepository->find($id);
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

            $this->emRepository->save($banque,true);

            return $this->redirectToRoute('app_banque');
        }

        return $this->renderForm('banque/editBanque.html.twig', [
            'form' => $form,
            'page_name' => 'Banque',
        ]);
    }

    #[Route('/banque/delete/{id}', name: 'banque_delete')]
    public function deleteBanque(ManagerRegistry $doctrine, int $id): Response
    {
        $banque = $this->emRepository->find($id);

        if (!$banque) {
            throw $this->createNotFoundException(
                'No banque found for id ' . $id
            );
        }

        $this->emRepository->remove($banque,true);

        return $this->redirectToRoute('app_banque');
    }

}