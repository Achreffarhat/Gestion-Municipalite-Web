<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Outils;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\OutilsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/reservation')]
class ReservationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository,PaginatorInterface $paginator,Request $request): Response
    {$data = $reservationRepository->findAll();
        $reservation = $paginator->paginate(
            $data, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
           4 /*limit per page*/
        );
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservation,
        ]);
    }
    
    // #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    // public function indezx(ReservationRepository $reservationRepository): Response
    // {
    //     return $this->render('reservation/index.html.twig', [
    //         'reservations' => $reservationRepository->findAll(),
    //     ]);
    // } 

    #[Route('/frontReservation', name: 'app_reservation_front', methods: ['GET'])]
    public function indexFront(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/FrontReservation.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    } 

    #[Route('/erreur', name: 'stock', methods: ['GET'])]
    public function stockerreur(): Response
    {
        return $this->render('reservation/stock.html.twig', []
        );
    }
    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Reservation $reservation, ReservationRepository $reservationRepository ,OutilsRepository     $outilsRepository , EntityManagerInterface $entityManager ): Response
    {
       // $form = $this->createForm(ReservationType::class, $reservation);
     //   $form->handleRequest($request);

     $outil=$entityManager->getRepository(Outils::class)->findOneBy(['id' => $reservation->getOutil()]);

      
    //   $outil = $this->getDoctrine()->getRepository(Outils::class)->findOneBy(['id' => $reservation->getOutil()]);
      if ( $outil->getQuantite() > $reservation->getQuantite() ) {
            $reservation->setDateDebut($reservation->getDateDebut()) ; 
            $reservation->setDateFin($reservation->getDateFin())  ; 
            $quantite = intval($outil->getQuantite()) - intval($reservation->getQuantite()) ;
           $outil->setQuantite(strval($quantite) ); 
            $reservation ->setQuantite($reservation->getQuantite()) ; 
            $reservation ->setOutil($reservation->getOutil()) ; 
            $reservation->setEtat("confirmé" ) ; 
            $reservationRepository->save($reservation, true);
            $outilsRepository->save($outil, true);


            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
      }
      else { 

        return $this->redirectToRoute('stock', [], Response::HTTP_SEE_OTHER);

      }
       
    }

    #[Route('/new/{id}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository , Outils $outils): Response
    {    if ($this->isCsrfTokenValid('get'.$outils->getId(), $request->request->get('_token'))) {
    }
    
        $reservation = new Reservation(); 
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setEtat('Non confirmé') ;
            $reservation->setOutil($outils) ;
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_conf', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ] ,  );
    
    
}
//
    #[Route('/conf', name: 'app_conf', methods: ['GET', 'POST'])]
    public function newreser(Request $request, ReservationRepository $reservationRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/confirmation.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    } 


     #[Route('/pdf/{id}', name: 'reservation_pdf', methods: ['GET'])]
     public function pdf(Reservation $reservation): Response
      {    $pdfOptions = new Options();
         $pdfOptions->set('defaultFont', 'Arial');
        
         // Instantiate Dompdf with our options
         $dompdf = new Dompdf($pdfOptions);
        
        
         // Retrieve the HTML generated in our twig file
         $html = $this->renderView('reservation/pdf.html.twig', [
             'reservation' => $reservation,
         ]);
        
         // Load HTML to Dompdf
         $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
         $dompdf->setPaper('A6', 'portrait');

         // Render the HTML as PDF
         $dompdf->render();

         // Output the generated PDF to Browser (force download)
         $dompdf->stream("reservation.pdf", [
             "Attachment" => true
         ]);
    return new Response();
    }

    

  

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation, true);
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }



    // #[Route('/pfd', name: 'reservation_pdf', methods: ['GET'])]
    // public function listr(ReservationRepository $reservationRepository): Response
    // {
        

    //     $pdfOptions = new Options();
    //     $pdfOptions->set('defaultFont', 'Arial');
        
    //     // Instantiate Dompdf with our options
    //     $dompdf = new Dompdf($pdfOptions);
    //     $l = $reservationRepository->findAll();
        
    //     // Retrieve the HTML generated in our twig file
    //     $html = $this->renderView('reservation/pdf.html.twig', [
    //         'reservation' =>$l,
    //     ]);
        
    //     // Load HTML to Dompdf
    //     $dompdf->loadHtml($html);
        
    //     // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
    //     $dompdf->setPaper('A4', 'portrait');

    //     // Render the HTML as PDF
    //     $dompdf->render();

    //     // Output the generated PDF to Browser (force download)
    //     $dompdf->stream("reservation.pdf", [
    //         "Attachment" => true
    //     ]);
    //     return new Response();
    // }
}

