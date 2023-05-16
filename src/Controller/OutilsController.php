<?php

namespace App\Controller;

use App\Entity\Outils;
use App\Entity\Urlizer;
use App\Form\OutilsType;
use App\Repository\OutilsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;



#[Route('/outils')]
class OutilsController extends AbstractController

{
    #[Route('/', name: 'app_outils_index', methods: ['GET'])]
    public function index3(OutilsRepository $outilsRepository,PaginatorInterface $paginator,Request $request): Response
    {$data = $outilsRepository->findAll();
        $outils = $paginator->paginate(
            $data, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
           4 /*limit per page*/
        );
        return $this->render('outils/index.html.twig', [
            'outils' => $outils,
        ]);
    }
    
   

    // #[Route('', name: 'app_outils_index', methods: ['GET'])]
    // public function index3(OutilsRepository $outilsRepository, PaginatorInterface $paginator, Request $request ): Response
    // {
       
    //     $data = $outilsRepository->findAll();
    //     $outils = $paginator->paginate($data,$request->query->getInt('page',1),2);
      
    //     return $this->render('outils/index.html.twig',[
    //     'outils' => $outils]);
    
    // ;
    // }

    // #[Route('/', name: 'app_outils_index', methods: ['GET'])]
    // public function index(OutilsRepository $outilsRepository): Response
    // {
    //     return $this->render('outils/index.html.twig', [
    //         'outils' => $outilsRepository->findAll(),
    //     ]);
    // }
    //  #[Route('/front', name: 'app_outils_indexfront', methods: ['GET'])]
    // public function indexfront(OutilsRepository $outilsRepository): Response
    // {
    //     return $this->render('outils/indexfront.html.twig', [
    //         'outils' => $outilsRepository->findAll(),
    //     ]);
    //  }

    //  #[Route('/front', name: 'app_outils_indexfront', methods: ['GET'])]
    // public function indexfront(OutilsRepository $outilsRepository, PaginatorInterface $paginator, Request $request): Response
    // {
    //     $data = $outilsRepository->findAll();
    //     $outils = $paginator->paginate($data,$request->query->getInt('page',1),6);
    //     return $this->render('outils/indexfront.html.twig',[
    //     'outils' => $outils]);
    
    // ;
    // }
    #[Route('/frontOutils', name: 'app_outils_indexfront', methods: ['GET'])]
    public function indexfront(OutilsRepository $outilsRepository,PaginatorInterface $paginator,Request $request): Response
    {$data = $outilsRepository->findAll();
        $outils = $paginator->paginate(
            $data, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
           4 /*limit per page*/
        );
        return $this->render('outils/indexfront.html.twig', [
            'outils' => $outils,
        ]);
    }

    #[Route('/new', name: 'app_outils_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OutilsRepository $outilsRepository): Response
    {
     
        $outil = new Outils();
        $form = $this->createForm(OutilsType::class, $outil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $outil->setImage($newFilename);
            $outilsRepository->save($outil, true);
          

            return $this->redirectToRoute('app_outils_index', [], Response::HTTP_SEE_OTHER);
        }
         $this->addFlash(
            'info',
            'added successfuly'
        );

        return $this->renderForm('outils/new.html.twig', [
            'outil' => $outil,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_outils_show', methods: ['GET'])]
    public function show(Outils $outil): Response
    {
        return $this->render('outils/show.html.twig', [
            'outil' => $outil,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_outils_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Outils $outil, OutilsRepository $outilsRepository): Response
    {
       
        $form = $this->createForm(OutilsType::class, $outil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
                $outil->setImage($newFilename);
            $outilsRepository->save($outil, true);
            

            return $this->redirectToRoute('app_outils_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->addFlash(
            'mod',
            'edit successfuly'
        );

        return $this->renderForm('outils/edit.html.twig', [
            'outil' => $outil,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_outils_delete', methods: ['POST'])]
    public function delete(Request $request, Outils $outil, OutilsRepository $outilsRepository): Response
    {
      
        if ($this->isCsrfTokenValid('delete'.$outil->getId(), $request->request->get('_token'))) {
            $outilsRepository->remove($outil, true);
        }
        // $this->addFlash(
        //     'mod',
        //     'edit successfuly'
        // );
        return $this->redirectToRoute('app_outils_index', [], Response::HTTP_SEE_OTHER);
    }
    //   public function exempleAction(FlashBagInterface $flashMessage)
    //   {
    //     $this ->flashMessage=$flashMessage ;
    //       // créer un message flash
    //       $this->addFlash('success', 'Votre message a été envoyé avec succès !');
    
    //     // rediriger l'utilisateur
    //   return $this->redirectToRoute('app_outils_index');
    //  }

}
   
