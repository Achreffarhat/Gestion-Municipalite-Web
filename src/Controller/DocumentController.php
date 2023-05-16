<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Urlizer;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/document')]
class DocumentController extends AbstractController
{
    #[Route('/', name: 'app_document_index', methods: ['GET'])]
    public function index(DocumentRepository $documentRepository): Response
    {
        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->findAll(),
        ]);
    }


    #[Route('/listdoc', name: 'app_document_listdoc', methods: ['GET'])]
    public function listdoc(DocumentRepository $documentRepository): Response
    {
        $documents = $documentRepository->findAll();
        $downloadUrl = 'https://example.com/download'; 
        return $this->render('document/listdoc.html.twig', [
            'documents' => $documents,
            'download_url' => $downloadUrl,
        ]);
    }

    #[Route('/new', name: 'app_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DocumentRepository $documentRepository, SluggerInterface $slugger ): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
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
               $document->setimage($newFilename);
               $documentRepository->save($document, true);

               return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        
        }

        return $this->renderForm('document/new.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_document_show', methods: ['GET'])]
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }
    // #[Route('/{id}', name: 'app_document_show', methods: ['GET'])]
    // public function show(Document $document): Response
    // {
    // // Generate download URL for the image file
    // $download_url = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $document->getImage();

    // return $this->render('document/show.html.twig', [
    //     'document' => $document,
    //     'download_url' => $download_url, // Make download URL available to the template
    // ]);
    // }



    #[Route('/{id}/edit', name: 'app_document_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Document $document, DocumentRepository $documentRepository): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
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
                $document->setImage($newFilename);
            $documentRepository->save($document, true);

            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('document/edit.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_document_delete', methods: ['POST'])]
    public function delete(Request $request, Document $document, DocumentRepository $documentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $documentRepository->remove($document, true);
        }

        return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/download', name: 'app_document_download', methods: ['GET'])]
    public function download(Document $document): Response
    {
        $response = new Response();
        $response->setContent(file_get_contents($this->getParameter('kernel.project_dir').'/public/uploads/'.$document->getimage()));
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$document->getimage().'"');

        return $response;
    }
}
