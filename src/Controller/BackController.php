<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{
    #[Route('/back', name: 'app_back')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        /** @var User $user */
		$user = $this->getUser();

		return match ($user->isVerified()) {
			true => $this->render("back/index.html.twig"),
			false => $this->render("admin/please-verify-email.html.twig"),
		};
    }
}
