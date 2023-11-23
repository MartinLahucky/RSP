<?php

namespace App\Controller;

use App\Entity\Clanek;
use App\Entity\KomentarClanek;
use App\Entity\Namitka;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\VerzeClanku;
use App\Form\UserRolesFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Role\Role as RoleRole;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class SecurityController extends AbstractController
{
    #[Route(path: '/download', name: 'app_download')]
    public function download(Request $request)
    {
        $clanek = $request->query->get('clanek');
        $verze = $request->query->get('verze');
        $soubor = $request->query->get('soubor');

        // Assume the file is stored in the web/uploads directory
        $path = $this->getParameter('public_dir') . '/clanky/' . $clanek . '/' . $verze . '/' . $soubor;
        $file_name = basename($path);

        // Create a BinaryFileResponse instance
        $response = new BinaryFileResponse($path);

        // Set the filename and the disposition
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file_name
        );

        // Return the response
        return $response;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route(path: '/select-roles/{id}', name: 'app_select_role')]
    public function selectRole(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordEncoder, $id): Response
    {
        $role = $doctrine->getManager()->getRepository(User::class)->find($id); //Najde na základě id záznam
        if (!$role) {
            throw $this->createNotFoundException('Nebyla nalezena role s tímto id ' . $id); //Error pokud není záznam nalezen
        }

        // Check if the user has the right to edit this role 'ADMIN'
        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) 
        {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }

        $form = $this->createForm(UserRolesFormType::class, $role);  //Tvorba nového formuléře podle vzoru ProductFormType

        $form->handleRequest($request);  //Předání dat z formuláře

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $password = $form->get('password')->getData();
            if (!empty($password)) 
            {
                $encodedPassword = $passwordEncoder->hashPassword($role, $password);
                $role->setPassword($encodedPassword);
            }

            $em = $doctrine->getManager(); // Objekt pro práci s entitami

            $em->flush(); // Provedení změn v databázi

            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/select-roles.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/create-user', name: 'app_create_user')]
    public function createUser(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordEncoder): Response
    {   
        if ($this->getUser()==null) 
        {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }
        // Create a new empty User entity
        $user = new User();

        // Create the form for the User entity
        $form = $this->createForm(UserRolesFormType::class, $user);

        // Handle the request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the password from the form
            $user = $form->getData();

            $password = $form->get('password')->getData();

            // If a password was entered, encode it
            if (!empty($password)) 
            {
                $encodedPassword = $passwordEncoder->hashPassword($user, $password);
                $user->setPassword($encodedPassword);
            }

            // Get the entity manager
            $em = $doctrine->getManager();

            // Persist the new user entity
            $em->persist($user);

            // Flush to save the new user entity to the database
            $em->flush();

            // Redirect to the home page or any other page
            return $this->redirectToRoute('app_home');
        }

        // Render the form view in your template
        return $this->render('security/create-user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/user-overview', name: 'app_user_overview')]
    public function userOverview(ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()==null) 
        {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }
        
        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) 
        {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }

        $users = $doctrine->getRepository(User::class)->findAll();

        return $this->render('security/user-overview.html.twig', [
            'users' => $users
        ]);
    }
    


    #[Route(path: '/author-articles-overiew', name: 'app_author_articles_overview')]
    public function authorArticlesOverviw(ManagerRegistry $doctrine): Response
    {
        // Zkontroluje prava
        if ($this->getUser() == null)
        {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }
        if (!in_array(Role::AUTOR->value, $this->getUser()->getRoles())) {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }

        // Nacteni clanku
        $manager = $doctrine->getManager();
        $user = $manager->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        if (!$user){
            return new Response('Uzivatel nenalezen');
        }

        $clanky = $manager->getRepository(Clanek::class)->findBy(['user' => $user->getId()]);
        if (!$clanky) {
            return new Response('Nebyly nalezeny zadne clanky'); //Error pokud není záznam nalezen
        }

        // Nacteni prvni verze clanku ke kazdemu clanku, abych mohl mohl zobrazit info ke kazdemu clanku
        $clanek_verze = array();
        foreach ($clanky as $clanek)
        {
            $verze = $manager->getRepository(VerzeClanku::class)->findOneBy(['clanek' => $clanek->getId()]);
            array_push($clanek_verze, $verze);
        }

        return $this->render('security/author-articles-overiew.html.twig',
        [
            'clanky' => $clanky,
            'clanek_verze' => $clanek_verze,
        ]);
    }

    #[Route(path: '/author-article-detail/{clanek_id}', name: 'app_author_article_detail')]
    public function authorArticleDetail(ManagerRegistry $doctrine, $clanek_id): Response
    {
        // Zkontroluje prava
        if ($this->getUser() == null) {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }
        if (!in_array(Role::AUTOR->value, $this->getUser()->getRoles())) {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }

        // Nacteni verzi clanku
        $manager = $doctrine->getManager();
        $user = $manager->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        if (!$user){
            return new Response('Uzivatel nenalezen');
        }

        $clanek = $manager->getRepository(Clanek::class)->findOneBy(['user' => $user->getId()]);
        if (!$clanek) {
            return new Response('Nebyly nalezeny zadne clanky');
        }

        $verze_clanku = $manager->getRepository(VerzeClanku::class)->findBy(['clanek' => $clanek->getId()]);
        if (!$verze_clanku) {
            return new Response('Nebyly nalezeny zadne verze clanku');
        }

        return $this->render('security/author-article-detail.html.twig',
        [
            'clanek_verze' => $verze_clanku,
        ]);
    }

    // Zde budou zobrazeno konverzace mezi autorem a redaktorem a bude zde link na stazeni dane verze clanku
    #[Route(path: '/article-comments/{verze_clanku_id}', name: 'app_article_comments')]
    public function articleComments(ManagerRegistry $doctrine, $verze_clanku_id): Response
    {
        // Zkontroluje prava
        if ($this->getUser() == null) {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }
        if (!in_array(Role::AUTOR->value, $this->getUser()->getRoles())
            && !in_array(Role::REDAKTOR->value, $this->getUser()->getRoles()))
        {
            return new Response("Pristup odepren");
        }

        // Nacteni infa o verzi vlanku
        $manager = $doctrine->getManager();
        $verze_clanku = $manager->getRepository(VerzeClanku::class)->find($verze_clanku_id);
        if (!$verze_clanku) {
            return new Response("Verze clanku nenalezena");
        }

        // Nacteni komentaru
        $komentare = $manager->getRepository(KomentarClanek::class)->findBy(['verze_clanku' => $verze_clanku->getId()]);

        // Nacteni namitky
        $namitka = $manager->getRepository(Namitka::class)->findOneBy(['clanek' => $verze_clanku->getClanek()]);

        // TODO: Ve twigu chybi textove pole pro vytvoreni noveho komentare
        return $this->render('security/article-comments.html.twig',
        [
            'clanek_verze' => $verze_clanku,
            'komentare' => $komentare,
            'namitka' => $namitka,
        ]);
    }

    // Zde budou zobrazeno konverzace mezi dvema recenzenty a redaktorem a bude zde link na stazeni dane verze clanku
    #[Route(path: '/task-comments/{ukol_id}', name: 'app_task_comments')]
    public function taskComments(ManagerRegistry $doctrine, $ukol_id): Response
    {
        // TODO: Dodelat pak v jinem user story
        return new Response("neimplementovano");
    }

    #[Route(path: '/show-namitka/{namitka_id}', name: 'app_show_namitka')]
    public function zobrazNamitku(ManagerRegistry $doctrine, $namitka_id): Response
    {
        $namitka = $doctrine->getManager()->getRepository(Namitka::class)->find($namitka_id);
        if (!$namitka) {
            return new Response("Chyba nacitani namitky");
        }

        return $this->render('security/show-namitka.html.twig',
        [
            'namitka' => $namitka,
        ]);
    }
}
