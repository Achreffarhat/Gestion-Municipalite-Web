<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Mime\Address;

class AdminController extends AbstractController
{
	#[Route('/admin', name: 'app_admin')]
	public function index(): Response
	{
		$this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

		/** @var User $user */
		$user = $this->getUser();

		return match ($user->isVerified()) {
			true => $this->render("admin/index.html.twig"),
			false => $this->render("admin/please-verify-email.html.twig"),
		};
	}

}
