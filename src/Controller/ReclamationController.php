<?php

namespace App\Controller;
use Dompdf\Dompdf;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\BarChart;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\TypeRepository;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Session\Session ; 

use App\Form\RechercheType;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_reclamation_index', methods: ['GET', 'POST'])]
    public function index( ReclamationRepository $reclamationRepository,EntityManagerInterface $entityManager, Request $request): Response
    {    
        $reclamations=$entityManager->getRepository(Reclamation::class)->findAll();
        $back = null;
        if($request->isMethod("POST")){
            if ( $request->request->get('optionsRadios')){
                $SortKey = $request->request->get('optionsRadios');
                switch ($SortKey){
                    case 'nom':
                        $reclamations = $reclamationRepository->SortBynom();
                        break;

                    case 'date':
                        $reclamations = $reclamationRepository->SortBydate();
                        break;

                

                }
            }
            else
            {
                $typee = $request->request->get('optionsearch');
                $value = $request->request->get('Search');
                switch ($typee){
                    case 'nom':
                        $reclamations = $reclamationRepository->findBynom($value);
                        break;

               

                    case 'prenom':
                        $reclamations = $reclamationRepository->findByprenom($value);
                        break;


                }
            }

            if ( $reclamations ){
                $back = "success";
            }else{
                $back = "failure";
            }}
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
            'back' => $back,

            ]);
       
    }


#[Route('/statisreclamation', name: 'app_reclamation_statisreclamation', methods: ['GET'])]
public function statisreclamation(ReclamationRepository $ReclamationRepository)
{
    //on va chercher les categories
    $rech = $ReclamationRepository->barDep();
    $arr = $ReclamationRepository->barArr();
    
    $bar = new barChart ();
    $bar->getData()->setArrayToDataTable(
        [['reclamation', 'Type'],
         ['Collecte des déchets', intVal($rech)],
         ['Éclairage public', intVal($arr)],
        

        ]
    );

    $bar->getOptions()->setTitle('les Reclamations');
    $bar->getOptions()->getHAxis()->setTitle('Nombre de reclamation');
    $bar->getOptions()->getHAxis()->setMinValue(0);
    $bar->getOptions()->getVAxis()->setTitle('Type');
    $bar->getOptions()->SetWidth(800);
    $bar->getOptions()->SetHeight(400);


    return $this->render('reclamation/statisreclamation.html.twig', array('bar'=> $bar )); 

} 

    #[Route('/listr', name: 'app_reclamation_listr', methods: ['GET'])]
    public function listr(ReclamationRepository $reclamationRepository): Response
    {
        

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $l = $reclamationRepository->findAll();
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reclamation/listr.html.twig', [
            'reclamations' =>$l,
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
        return new Response();
    }


    
    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager,ManagerRegistry $doctrine)
    {   $reclamation = new reclamation();
         $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        $myDictionary = array(
            "tue", "merde", "pute",
            "gueule",
            "débile",
            "con",
            "abruti",
            "clochard",
            "sang"
        );
        dump($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myText = $request->get("reclamation")['description'];
            $badwords = new PhpBadWordsController();
            $badwords->setDictionaryFromArray($myDictionary)
                ->setText($myText);
            $check = $badwords->check();
            dump($check);
            if ($check) {
                $this->addFlash(
                    'erreur',
                    'Mot inapproprié! , Reclamation n est pas ajouté'
                ); } 
                else {

                    $this->entityManager->persist($reclamation);
                    $this->entityManager->flush();
                    // $entityManager = $this->getManager();
                    // $entityManager->persist($reclamation);



                $entityManager->flush();
                $this->addFlash(
                    'info',
                    'Reclamation ajouté !!'
                );
            }

            return $this->redirectToRoute('app_reclamation_new', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),

        ]);
    }

    
    
    
    
    





    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }
        $this->addFlash('message', 'Votre ajout est complete');


        $this->addFlash(
            'info',
            ' le Reclamation a été supprimer', 
        );



        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    

    
   
}
