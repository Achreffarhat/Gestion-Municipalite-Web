<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\BarChart;

class StatisticController extends AbstractController
{
    /**
     * @Route("/statistic", name="app_statistic")
     */
    public function index(): Response
    {
        return $this->render('statistic/stats.html.twig', [
            'controller_name' => 'StatisticController',
        ]);
    }


   /**
    * @Route("/stats", name="rec_stat")
    */
    public function statistiques (ReclamationRepository $rep){

        //chercher les types de reclamation

        $reclamations = $rep->countByNbr();

        $recType = [];
        $recCount = [];


        foreach($reclamations as $reclamation){

            // $recType[] = $reclamation->getType();
            $recType[] = $reclamation ['nom'];
            $recCount[]= $reclamation ['nbr'];
            // $recCount[] = count($recType);
        }
        return $this->render('statistic/stats.html.twig', [
            'recType' => json_encode($recType),
            'recCount' => json_encode($recCount),


        ]);

        
    }
}