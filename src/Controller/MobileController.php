<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Security\AppAuthenticator;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Form\User2Type;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use App\Entity\Reclamation;
use App\Entity\Outils;
use App\Repository\OutilsRepository;
use App\Repository\CategorieRepository;
use App\Repository\ReclamationRepository;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

#[Route('/mobile')]
class MobileController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/addReclamation", name="ajouteReclamation")
     */
    public function add_reclamation(NormalizerInterface $normalizable, EntityManagerInterface $entityManager, Request $request): JsonResponse
    {

        $reclamation = new Reclamation();

        // $daterec= $request->get("date_reclamation");
        $reclamation->setNom($request->get("nom"));
        $reclamation->setPrenom($request->get("prenom"));
        $reclamation->setEmail($request->get("email"));
        //  $reclamation->setTel($request->get("tel"));
        $reclamation->setEtat($request->get("etat"));
        $reclamation->setDescription($request->get("description"));
        // $reclamation->setDateReclamation($request->get("date_reclamation"));
        // $reclamation->setDateReclamation(new \DateTime($daterec));



        $entityManager->persist($reclamation);
        $entityManager->flush();
        return new JsonResponse([
            'success' => "reclamation has been added"
        ]);
    }



    /**
     * @Route("/addCategorie", name="ajouteCategorie")
     */
    public function add_categorie(NormalizerInterface $normalizable, EntityManagerInterface $entityManager, Request $request)
    {
        $categorie = new Categorie();


        $categorie->setLabelCat($request->get("labelcat"));




        $entityManager->persist($categorie);
        $entityManager->flush();
        return new JsonResponse([
            'success' => "categorie has been added"
        ]);
    }





    /**
     * @Route("/editeReclamation/{id}", name="update_reclamation")
     */

    public function modifierReclamationAction(Request $request, $id, ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager)
    {

        $reclamation  = $reclamationRepository
            ->find($id);

        $reclamation->setNom($request->get("nom"));
        $reclamation->setPrenom($request->get("prenom"));
        $reclamation->setEmail($request->get("email"));
        // $reclamation->setTel($request->get("tel"));
        $reclamation->setEtat($request->get("etat"));
        $reclamation->setDescription($request->get("description"));

        $entityManager->persist($reclamation);
        $entityManager->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclamation);
        return new JsonResponse("Reclamation a ete modifiee avec success.");
    }


    /**
     * @Route("/editeCategorie/{id}", name="update_categorie")
     */

    public function modifierCategorieAction(Request $request, $id, CategorieRepository $categorieRepository, EntityManagerInterface $entityManager)
    {


        $categorie = $categorieRepository
            ->find($id);

        $categorie->setLabelCat($request->get("labelcat"));


        $entityManager->persist($categorie);
        $entityManager->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($categorie);
        return new JsonResponse("Categorie a ete modifiee avec success.");
    }

    /**
     * @Route("/delReclamation/{id}", name="delreclamation")
     */


    public function delReclamationoffre(Request $request, NormalizerInterface $normalizer, ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager)
    {

        $rec = $reclamationRepository->find($request->get("id"));
        $entityManager->remove($rec);
        $entityManager->flush();
        $jsonContent = $normalizer->normalize($rec, 'json', ['reclamation' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/delCategorie/{id}", name="delcategorie")
     */


    public function delCategorieoffre(Request $request, NormalizerInterface $normalizer, CategorieRepository $categorieRepository, EntityManagerInterface $entityManager)
    {
        $rec = $categorieRepository->find($request->get("id"));
        $entityManager->remove($rec);
        $entityManager->flush();
        $jsonContent = $normalizer->normalize($rec, 'json', ['categorie' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }
    /**  
     * @Route("/listReclamations", name="app_mobileee")
     */
    public function listrec(NormalizerInterface $normalizer, ReclamationRepository $reclamationRepository): Response
    {
        $reclamations = $reclamationRepository->findAll();

        $jsonContent = $normalizer->normalize($reclamations, 'json', ['groups' => 'reclamations']);

        return new JsonResponse($jsonContent);
    }
    /**
     * @Route("/listCategorie", name="app_mobile")
     */
    public function index2(NormalizerInterface $normalizer, CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();


        $jsonContent = $normalizer->normalize($categories, 'json', ['groups' => 'categories']);

        return new JsonResponse($jsonContent);
    }

    #[Route('/user', name: 'app_mobile_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'nom' => $user->getNomUtil(),
                'prenom' => $user->getPrenomUtil(),
                'tel' => $user->getTel(),
                'adresse' => $user->getAdresse(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/Apilogin', name: 'app_login_api')]
    public function ApiLogin(Request $req , UserRepository $repository,UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {
        $email = $req->query->get('email');
        $password = $req->query->get('password');
       
        $user = $repository->findOneBy(['email'=>$email]);
  
        if($user) {
            if($userPasswordHasher->isPasswordValid($user, $password)) {
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                return new JsonResponse($formatted);
            }
            else {
                return new JsonResponse("password incorrect !");
            }
        }
        else 
        {
            return new JsonResponse("user not found !");
        }
       
    
       
    }

    #[Route('/{id}', name: 'app_mobile_show', methods: ['GET'])]
    public function show(User $user): JsonResponse
    {
        $data = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'nom' => $user->getNomUtil(),
            'prenom' => $user->getPrenomUtil(),
            'tel' => $user->getTel(),
            'adresse' => $user->getAdresse(),
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/delUser/{id}", name="delUser")
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager, UserRepository $userRepository, NormalizerInterface $normalizer)
    {
        $us = $userRepository->find($request->get("id"));
        $entityManager->remove($us);
        $entityManager->flush();
        $jsonContent = $normalizer->normalize($us, 'json', ['user' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/editeUser/{id}", name="update_user")
     */

    public function modifierUser(Request $request, $id, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {

        $user  = $userRepository
            ->find($id);

        $user->setEmail($request->get("email"));
        $user->setNomUtil($request->get("nom"));
        $user->setPrenomUtil($request->get("prenom"));
        $user->setTel($request->get("tel"));
        $user->setAdresse($request->get("adresse"));

        $entityManager->persist($user);
        $entityManager->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
        return new JsonResponse("Utilisateur a ete modifiee avec success.");
    }


    /**
     * @Route("/register", name="register_user")
     */
    public function add_user(NormalizerInterface $normalizable, EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {

        $user = new User();


        $user->setEmail($request->get("email"));
        $user->setRoles(['ROLE_CITOYEN']);
        $user->setPassword(
             $userPasswordHasher->hashPassword(
                 $user,
                $request->get("password")
           ) );

        $user->setNomUtil($request->get("nom"));
        $user->setPrenomUtil($request->get("prenom"));

        $user->setTel($request->get("tel"));
        $user->setAdresse($request->get("adresse"));
        $user->setIsVerified(true);

        $entityManager->persist($user);
        $entityManager->flush();
        return new JsonResponse([
            'success' => "user has been added"
        ]);
    }
    /**
    * @Route("/Addoutils", name="ajouteoutils")
    */
    public function add_outils( NormalizerInterface $normalizable, EntityManagerInterface $entityManager, Request $request)
    {

      $outils = new Outils();

       $outils->setLabelOutils($request->get("label_outils"));
       $outils->setQuantite($request->get("quantite"));
       $outils->setImage($request->get("image"));
       
       
       

     $entityManager->persist($outils);
       $entityManager->flush();
       return new JsonResponse([
          'success' => "outils has been added"
       ]);
   }

   /**
     * @Route("/allOutils", name="app_mobile_outils")
     */
    public function outils(NormalizerInterface $normalizer, OutilsRepository $outilRepository): Response
    {
        $outils = $outilRepository->findAll();
       

       $jsonContent = $normalizer->normalize($outils,'json',['groups'=>'outils']);

       return new JsonResponse($jsonContent);


    }

    /**
     * @Route("/editeOutils/{id}", name="update_outils")
     */

     public function modifierOutilsAction(Request $request, $id , OutilsRepository $outilsRepository,EntityManagerInterface $entityManager)
     {
         
         $outil  = $outilsRepository
             ->find($id);
 
             $outil->setLabelOutils($request->get("label_outils"));
             $outil->setQuantite($request->get("quantite"));
             $outil->setImage($request->get("image"));
 
         $entityManager->persist($outil);
         $entityManager->flush();
 
         $serializer = new Serializer([new ObjectNormalizer()]);
         $formatted = $serializer->normalize($outil);
         return new JsonResponse("Outils a ete modifiee avec success.");
     }

     /**
    * @Route("/delete/{id}", name="delete outils")
    */
   public function deleteoutils(Request $request, NormalizerInterface $normalizer, $id, EntityManagerInterface $entityManager, OutilsRepository $repository): Response
   {
     
    $rec = $repository->find($request->get("id"));
    $entityManager->remove($rec);
    $entityManager->flush();
    $jsonContent = $normalizer->normalize($rec, 'json', ['outils' => 'post:read']);
    return new Response(json_encode($jsonContent));
   }
   
}
