<?php

namespace App\Controller;

use App\Entity\Clanek;
use App\Entity\KomentarClanek;
use App\Entity\KomentarUkol;
use App\Entity\Namitka;
use App\Entity\Posudek;
use App\Entity\Role;
use App\Entity\Ukol;
use App\Entity\User;
use App\Entity\VerzeClanku;
use App\Form\CreateNamitkaType;
use App\Form\KomentarType;
use App\Form\UserRolesFormType;
use App\Form\UserEditFormType;
use App\Form\ZmenaStavuType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Role\Role as RoleRole;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class SecurityController extends AbstractController
{
    #[Route(path: '/download', name: 'app_download')]
    public function download(Request $request): BinaryFileResponse
    {
        $path = $this->getParameter('public_dir');
        $slozka = $request->query->get('slozka');

        if ($slozka != null && $slozka == 'posudky')
        {
            $posudek_id = $request->query->get('posudek_id');
            $posudek_soubor = $request->query->get('posudek_soubor');
            $path = $path . '/posudky/' . $posudek_id . '/' . $posudek_soubor;
        }

        else
        {
            $clanek = $request->query->get('clanek');
            $verze = $request->query->get('verze');
            $soubor = $request->query->get('soubor');

            // Assume the file is stored in the web/uploads directory
            $path = $path . '/clanky/' . $clanek . '/' . $verze . '/' . $soubor;
        }

        $file_name = basename($path);
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
            return new Response("Chyba hledani uzitele");
        }

        // Check if the user has the right to edit this role 'ADMIN'
        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) 
        {
            return new Response("Pristup zamitnut");
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
            return new Response("Pristup zamitnut");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
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

    #[Route(path: '/user-profile', name: 'app_user_profile')]
    public function userProfile(ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()==null) 
        {
            return new Response("Pristup zamitnut");
        }

        $user = $doctrine->getManager()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        return $this->render('security/user-profile.html.twig', [
            'user' => $user
        ]);
    }

    #[Route(path: '/edit-user-profile', name: 'app_edit_user_profile')]
    public function editUserProfile(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordEncoder): Response
    {
        if ($this->getUser()==null) 
        {
            return new Response("Pristup zamitnut");
        }

        $user = $doctrine->getManager()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        $form = $this->createForm(UserEditFormType::class, $user); 

        $form->handleRequest($request);  //Předání dat z formuláře

        if ($form->isSubmitted()) 
        {
            if($form->isValid())
            {
                $password = $form->get('password')->getData();

                if (!empty($password)) 
                {
                    $encodedPassword = $passwordEncoder->hashPassword($user, $password);
                    $user->setPassword($encodedPassword);
                }

                $em = $doctrine->getManager(); // Objekt pro práci s entitami

                $em->flush(); // Provedení změn v databázi

                return $this->redirectToRoute('app_home');
            }
            //$doctrine()->getManager()->refresh();
            $doctrine->getManager()->refresh($user);
        }

        return $this->render('security/edit-user-profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/user-overview', name: 'app_user_overview')]
    public function userOverview(ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()==null) 
        {
            return new Response("Pristup zamitnut");
        }
        
        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) 
        {
            return new Response("Pristup zamitnut");
        }

        $users = $doctrine->getRepository(User::class)->findAll();

        return $this->render('security/user-overview.html.twig', [
            'users' => $users
        ]);
    }
    


    #[Route(path: '/author-articles-overview', name: 'app_author_articles_overview')]
    public function authorArticlesOverviw(ManagerRegistry $doctrine): Response
    {
        // Zkontroluje prava
        if ($this->getUser() == null)
        {
            return new Response("Pristup zamitnut");
        }
        if (!in_array(Role::AUTOR->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }

        // Nacteni clanku
        $manager = $doctrine->getManager();
        $user = $manager->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        if (!$user){
            return new Response('Uzivatel nenalezen');
        }

        $clanky = $manager->getRepository(Clanek::class)->findBy(['user' => $user->getId()]);
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
            return new Response("Pristup zamitnut");
        }
        if (!in_array(Role::AUTOR->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }

        // Nacteni verzi clanku
        $manager = $doctrine->getManager();
        $verze_clanku = $manager->getRepository(VerzeClanku::class)->findBy(['clanek' => $clanek_id]);
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
    public function articleComments(Request $request, ManagerRegistry $doctrine, $verze_clanku_id): Response
    {
        // Zkontroluje prava
        if ($this->getUser() == null) {
            return new Response("Pristup zamitnut");
        }
        if (!in_array(Role::AUTOR->value, $this->getUser()->getRoles()) &&
            !in_array(Role::REDAKTOR->value, $this->getUser()->getRoles()) &&
            !in_array(Role::SEFREDAKTOR->value, $this->getUser()->getRoles()))
        {
            return new Response("Pristup zamitnut");
        }

        // Nacteni infa o verzi vlanku
        $manager = $doctrine->getManager();
        $verze_clanku = $manager->getRepository(VerzeClanku::class)->find($verze_clanku_id);
        if (!$verze_clanku) {
            return new Response("Verze clanku nenalezena");
        }

        $form = $this->createForm(KomentarType::class);
        $form->handleRequest($request);  //Předání dat z formuláře

        if ($form->isSubmitted() && $form->isValid())
        {
            $komentar = new KomentarClanek();
            $komentar->setVerzeClanku($verze_clanku);
            $komentar->setUser($this->getUser());
            $komentar->setDatum(date('d.m.Y'));
            $komentar->setText($form->get('komentar')->getData());

            $manager->persist($komentar);
            $manager->flush();

            return $this->redirectToRoute('app_article_comments', ['verze_clanku_id' => $verze_clanku->getId()]);
        }

        // Nacteni komentaru
        $komentare = $manager->getRepository(KomentarClanek::class)->findBy(['verze_clanku' => $verze_clanku->getId()]);

        // Nacteni namitky
        $namitka = $manager->getRepository(Namitka::class)->findOneBy(['clanek' => $verze_clanku->getClanek()]);

        // Nacteni posudku
        $posudek1 = null;
        $posudek2 = null;
        $posudky = $manager->getRepository(Posudek::class)->findBy(['clanek' => $verze_clanku->getClanek()->getId()]);
        if ($posudky != null)
        {
            if (count($posudky) == 1) {
                $posudek1 = $posudky[0];
            }
            else if (count($posudky) > 1)
            {
                $posudek1 = $posudky[0];
                $posudek2 = $posudky[1];
            }
        }

        return $this->render('security/article-comments.html.twig',
        [
            'form' => $form->createView(),
            'clanek_verze' => $verze_clanku,
            'komentare' => $komentare,
            'namitka' => $namitka,
            'posudek1' => $posudek1,
            'posudek2' => $posudek2,
        ]);
    }

    #[Route(path: '/redaction-comments/{verze_clanku_id}', name: 'app_redaction_comments')]
    public function redactionComments(Request $request, ManagerRegistry $doctrine, $verze_clanku_id): Response
    {
        // Zkontroluje prava
        if ($this->getUser() == null) {
            return new Response("Pristup zamitnut");
        }
        if (!in_array(Role::REDAKTOR->value, $this->getUser()->getRoles()) &&
            !in_array(Role::SEFREDAKTOR->value, $this->getUser()->getRoles()) &&
            !in_array(Role::RECENZENT->value, $this->getUser()->getRoles()))
        {
            return new Response("Pristup zamitnut");
        }

        // Nacteni infa o verzi vlanku
        $manager = $doctrine->getManager();
        $verze_clanku = $manager->getRepository(VerzeClanku::class)->find($verze_clanku_id);
        if (!$verze_clanku) {
            return new Response("Verze clanku nenalezena");
        }

        $form = $this->createForm(KomentarType::class);
        $form->handleRequest($request);  //Předání dat z formuláře

        if ($form->isSubmitted() && $form->isValid())
        {
            $komentar = new KomentarUkol();
            $komentar->setVerzeClanku($verze_clanku);
            $komentar->setUser($this->getUser());
            $komentar->setDatum(date('d.m.Y'));
            $komentar->setText($form->get('komentar')->getData());

            $manager->persist($komentar);
            $manager->flush();

            return $this->redirectToRoute('app_redaction_comments', ['verze_clanku_id' => $verze_clanku->getId()]);
        }

        // Nacteni komentaru
        $komentare = $manager->getRepository(KomentarUkol::class)->findBy(['verze_clanku' => $verze_clanku->getId()]);

        // Nacteni namitky
        $namitka = $manager->getRepository(Namitka::class)->findOneBy(['clanek' => $verze_clanku->getClanek()]);

        // Nacteni posudku
        $posudek1 = null;
        $posudek2 = null;
        $posudky = $manager->getRepository(Posudek::class)->findBy(['clanek' => $verze_clanku->getClanek()->getId()]);
        if ($posudky != null)
        {
            if (count($posudky) == 1) {
                $posudek1 = $posudky[0];
            }
            else if (count($posudky) > 1)
            {
                $posudek1 = $posudky[0];
                $posudek2 = $posudky[1];
            }
        }

        return $this->render('security/article-comments.html.twig',
            [
                'form' => $form->createView(),
                'clanek_verze' => $verze_clanku,
                'komentare' => $komentare,
                'namitka' => $namitka,
                'posudek1' => $posudek1,
                'posudek2' => $posudek2,
            ]);
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

    #[Route(path: '/create-namitka/{clanek_id}', name: 'app_create_namitka')]
    public function vytvorNamitku(Request $request, ManagerRegistry $doctrine, $clanek_id): Response
    {
        if ($this->getUser() == null ||
            !in_array(Role::AUTOR->value, $this->getUser()->getRoles())){
            return new Response("Pristup zamitnut");
        }

        $em = $doctrine->getManager();
        $clanek = $em->getRepository(Clanek::class)->findOneBy(['id' => $clanek_id]);
        if (!$clanek) {
            return new Response("Chyba nacitani clanku!");
        }

        $form = $this->createForm(CreateNamitkaType::class);
        $form->handleRequest($request);  //Předání dat z formuláře

        if ($form->isSubmitted() && $form->isValid())
        {
            $namitka = new Namitka();
            $namitka->setClanek($clanek);
            $namitka->setDatum(date('d.m.Y'));
            $namitka->setTextNamitky($form->get('text_namitky')->getData());

            $em->persist($namitka);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/create-namitka.html.twig',
        [
            'form' => $form->createView(),
            'clanek' => $clanek,
        ]);
    }

    #[Route(path: '/prehled-clanku-schvaleni', name: 'app_prehled_clanku_schvaleni')]
    public function prehledClankuSchvaleni(ManagerRegistry $doctrine): Response
    {
        if ($this->getUser() == null ||
            !in_array(Role::SEFREDAKTOR->value, $this->getUser()->getRoles())){
            return new Response("Pristup zamitnut");
        }

        $clanky = $doctrine->getManager()->getRepository(Clanek::class)->findBy(['stav_redakce' => \App\Entity\StavRedakce::VYJADRENI_SEFREDAKTORA->value]);
        return $this->render('security/prehled-clanku-schvaleni.html.twig',
        [
            'clanky' => $clanky,
        ]);
    }

    #[Route(path: '/zmenit-stav-clanku/{clanek_id}', name: 'app_zmenit_stav_clanku')]
    public function zmenitStavClanku(Request $request, ManagerRegistry $doctrine, $clanek_id): Response
    {
        if ($this->getUser() == null) {
            return new Response("Pristup zamitnut");
        }
        if (!in_array(Role::SEFREDAKTOR->value, $this->getUser()->getRoles()) &&
            !in_array(Role::REDAKTOR->value, $this->getUser()->getRoles()))
        {
            return new Response("Pristup zamitnut");
        }

        $em = $doctrine->getManager();
        $clanek = $em->getRepository(Clanek::class)->find($clanek_id);
        if (!$clanek) {
            return new Response("Chyba aktualizovani clanku!");
        }

        $stav_autor = $request->query->get('stav_autor');
        $stav_redakce = $request->query->get('stav_redakce');

        $em = $doctrine->getManager();
        $clanek = $em->getRepository(Clanek::class)->find($clanek_id);
        if (!$clanek) {
            return new Response("Chyba aktualizovani clanku!");
        }

        if ($stav_autor) {
            $clanek->setStavAutor($stav_autor);
        }
        if ($stav_redakce) {
            $clanek->setStavRedakce($stav_redakce);
        }

        $em->persist($clanek);
        $em->flush();

        // Presmerovat zpet
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route(path: '/zmenit-stav-clanku-formular/{clanek_id}', name: 'app_zmenit_stav_clanku_formular')]
    public function zmenitStavClankuFormular(Request $request, ManagerRegistry $doctrine, $clanek_id): Response
    {
        if ($this->getUser() == null) {
            return new Response("Pristup zamitnut");
        }
        if (!in_array(Role::SEFREDAKTOR->value, $this->getUser()->getRoles()) &&
            !in_array(Role::REDAKTOR->value, $this->getUser()->getRoles()))
        {
            return new Response("Pristup zamitnut");
        }

        $em = $doctrine->getManager();
        $clanek = $em->getRepository(Clanek::class)->find($clanek_id);
        if (!$clanek) {
            return new Response("Chyba aktualizovani clanku!");
        }

        $form = $this->createForm(ZmenaStavuType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $stav_autor = $form->get('stavAutor')->getData();
            $stav_redakce = $form->get('stavRedakce')->getData();

            if ($stav_autor) {
                $clanek->setStavAutor($stav_autor);
            }
            if ($stav_redakce) {
                $clanek->setStavRedakce($stav_redakce);
            }

            $em->persist($clanek);
            $em->flush();

            return $this->redirectToRoute('app_clanky_overview');
        }

        return $this->render('home/zmenit-stav-clanek.html.twig', ['form' => $form, 'clanek' => $clanek]);
    }
}
