<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontEmployeController extends AbstractController
{
    #[Route('/frontemploye', name: 'app_frontemploye')]
    public function index(): Response
    {
        return $this->render('frontemploye/employe.html.twig'
        );
    }
    

}
