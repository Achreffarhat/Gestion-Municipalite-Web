<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\Vehicule1Type;
use App\Repository\VehiculeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/vehicule')]
class VehiculeController extends AbstractController
{   
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/new', name: 'app_vehicule_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VehiculeRepository $vehiculeRepository): Response
    {
        $vehicule = new Vehicule();
        $form = $this->createForm(Vehicule1Type::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vehiculeRepository->save($vehicule, true);

            return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->addFlash(
            'info',
            'Added successfully!'
        );

        return $this->renderForm('vehicule/new.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_vehicule_show', methods: ['GET','POST'])]
    public function show(Vehicule $vehicule) : Response
    {
        return $this->render('vehicule/show.html.twig', [
            'vehicule' => $vehicule,
        ]);
    }

    #[Route('/', name: 'app_vehicule_index', methods: ['GET'])]
    public function index(VehiculeRepository $vehiculeRepository): Response
    {
        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehiculeRepository->findAll(),
        ]);
    }


   

    #[Route('/{id}/edit', name: 'app_vehicule_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vehicule $vehicule, VehiculeRepository $vehiculeRepository): Response
    {
        $form = $this->createForm(Vehicule1Type::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vehiculeRepository->save($vehicule, true);

            return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->addFlash(
            'mod',
            'Update successfully!'
        );

        return $this->renderForm('vehicule/edit.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vehicule_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicule $vehicule, VehiculeRepository $vehiculeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicule->getId(), $request->request->get('_token'))) {
            $vehiculeRepository->remove($vehicule, true);
        }
        $this->addFlash(
            'del',
            'delete successfully!'
        );

        return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
    }
   
    


    

 /**
     * @Route("/search", name="rechercheveh", methods={"GET"})
     */

//      public function searchoffreajax(Request $request, VehiculeRepository $FootRepository): Response
//      {
//          $FootRepository = $this->getDoctrine()->getRepository(Vehicule::class);
//          $requestString = $request->get('searchValue');
//          $foot = $FootRepository->findbymarque($requestString);
 
//          return $this->render('arbitre_match/index.html.twig', [
//              "b" => $foot
//          ]);
//      }



}
