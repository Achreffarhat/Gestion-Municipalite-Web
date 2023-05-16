<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/afficherr", name="display_cat")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {   $cat=$entityManager->getRepository(Categorie::class)->findAll();
        // $cat=$this->getDoctrine()->getManager()->getRepository(Categorie::class)->findAll();
        // $arb=$paginator->paginate(
        //     $amir,
        //     $request->query->getInt('page',1),
        //     3

        // ) ;

        return $this->render('categorie/list.html.twig', [
            'a'=>$cat
        ]);
    }

    /**
     * @Route("/addcat", name="addcat")
     */
    public function addcategorie(EntityManagerInterface $entityManager , Request $request): Response
    {
        $foot = new Categorie();
        $form=$this->createForm(CategorieType::class,$foot);
        $form->HandleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($foot);
            $this->entityManager->flush();
            // $em= $this->getDoctrine()->getManager();
            // $em->persist($foot);
            // $em->flush();
            return $this->redirectToRoute('display_cat');
        }
        return $this->render('categorie/index.html.twig',['a'=>$form->createView()]);

    }

    #[Route('/PDF', name: 'app_reclamation_PDF', methods: ['GET'])]
    public function listr(CategorieRepository $categorieRepository): Response
    {
        

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $l = $categorieRepository->findAll();
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('categorie/Pdf.html.twig', [
            'categories' =>$l,
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



     /**
     * @Route("/supprimer/{id}", name="supp_cat")
     */
    public function supprimer(Categorie $foot): Response
    {
        $this->entityManager->remove($foot);
        $this->entityManager->flush();
        // $em = $this->getDoctrine()->getManager();
        // $em-> remove($foot);
        // $em->flush();
        return $this->redirectToRoute('display_cat');
    }

      /**
     * @Route("/modifierMatch/{id}", name="modifiercat")
     */
    public function modifiercategorie(Request $request,$id,EntityManagerInterface $entityManager): Response
    {
        $foot=$entityManager->getRepository(Categorie::class)->find($id);
        // $foot = $this->getDoctrine()->getManager()->getRepository(Categorie::class)->find($id);
        $form=$this->createForm(CategorieType::class,$foot);
        $form->HandleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();
            // $em= $this->getDoctrine()->getManager();

            // $em->flush();
            return $this->redirectToRoute('display_cat');
        }
        return $this->render('categorie/update.html.twig',['a'=>$form->createView()]);

    }

    

    // /**
    //  * @Route("/search", name="rechercheveh", methods={"GET"})
    //  */

    //  public function searchoffreajax(Request $request, CategorieRepository $FootRepository): Response
    //  {
    //      $FootRepository = $this->getDoctrine()->getRepository(Categorie::class);
    //      $requestString = $request->get('searchValue');
    //      $foot = $FootRepository->findBycat($requestString);
 
    //      return $this->render('arbitre_match/index.html.twig', [
    //          "a" => $foot
    //      ]);
    //  }

    //   /**
    //  * @Route("/TrierspcASC", name="triespc",methods={"GET"})
    //  */

    // public function trierSpecialite(Request $request, CategorieController $ArbitreMatchRepository): Response
    // {

    //     $ArbitreMatchRepository = $this->getDoctrine()->getRepository(Categorie::class);
    //     $arb = $ArbitreMatchRepository->trie();

    //     return $this->render('arbitre_match/index.html.twig', [
    //         'a' => $arb,
    //     ]);
    // }

    /**
     * @Route("/listArb", name="listArb", methods={"GET"})
     */

  

}
