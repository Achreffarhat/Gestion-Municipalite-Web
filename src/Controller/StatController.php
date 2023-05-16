<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatController extends AbstractController
{
    #[Route('/stat', name: 'app_stat')]
    public function index(): Response
    {
        return $this->render('stat/index.html.twig', [
            'controller_name' => 'StatController',
        ]);
    }

/**
     * @Route("/stats", name="stats")
     */
    public function statistiques(VehiculeRepository $produitRepository,EntityManagerInterface $entityManager){
        

        $data=$entityManager->getRepository(Vehicule::class)->findAll();
        // $entityManager = $this->getDoctrine()->getManager();
        // $data = $entityManager->getRepository(Vehicule::class)->findAll();


        $marqData = array_map(function($datum) {
            return $datum->getId();
        }, $data);
        
        $prixData = array_map(function($datu) {
            return $datu->getId();
        }, $data);

        return $this->render('stat/index.html.twig', [
            'marqData' => json_encode($marqData),
            'prixData'=> json_encode($prixData)
        ]);

    }

}


