<?php

namespace App\Controller;


use App\Entity\Clanek;
use App\Entity\KomentarClanek;
use App\Entity\KomentarUkol;
use App\Entity\Namitka;
use App\Entity\Posudek;
use App\Entity\RecenzniRizeni;
use App\Entity\Tisk;
use App\Entity\Ukol;
use App\Entity\VerzeClanku;
use App\Form\VerzeClanekFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Role;
use App\Entity\User;
use App\Form\ClanekFormType;
use App\Form\RecenzniRizeniFormType;
use App\Form\TiskFormType;
use App\Form\UkolFormType;
use App\Form\PosudekType;
use App\Form\RulesType;
use App\Form\ZmenaStavuType;
use DateTime;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\UnicodeString;

class HomeController extends AbstractController

{
    #[Route('/home', name: 'app_home')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $searchTerm = $request->query->get('search', '');
        $authorFilter = $request->query->get('autor-filter', '');

        $manager = $doctrine->getManager();
        //$users = $manager->getRepository(User::class)->findAll();
        //$clanky = $manager->getRepository(Clanek::class)->findBy(['stav_autor' => \App\Entity\StavAutor::PRIJATO]);

        if (!empty($searchTerm) || !empty($authorFilter)) 
        {
            $clanky = $manager->getRepository(Clanek::class)->findByFilters($searchTerm, $authorFilter);
        } else 
        {
            $clanky = $manager->getRepository(Clanek::class)->findBy(['stav_autor' => \App\Entity\StavAutor::PRIJATO]);
        }

        // Nacist datumy vydani (tisku) a soubory clanku
        $datumy = array();
        $verze_clanku = array();
        $soubory = array();
        $authors = array();
        $authorsIds = array();
        foreach ($clanky as $clanek)
        {
            $recenzni_rizeni = $manager->getRepository(RecenzniRizeni::class)->findOneBy(['id' => $clanek->getRecenzniRizeni()]);
            if (!$recenzni_rizeni) {
                return new Response("Chyba načítání článků");
            }

            $tisk = $manager->getRepository(Tisk::class)->findOneBy(['id' => $recenzni_rizeni->getTisk()]);
            if (!$tisk) {
                return new Response("Chyba načítání článků");
            }

            $verze =
                $manager->getRepository(VerzeClanku::class)->findBy(['clanek' => $clanek->getId()]);
            if (!$verze) {
                return new Response("Chyba načítání článků");
            }

            $author = $manager->getRepository(User::class)->findOneBy(['id' => $clanek->getUser()->getId()]);
            if (!$author) {
                return new Response("Chyba načítání článků");
            }

            array_push($authors, $author->getFirstName().' '.$author->getLastName());
            array_push($authorsIds, $author->getId());
            array_push($datumy, $tisk->getDatum());
            array_push($verze_clanku, $verze[count($verze) - 1]);
            array_push($soubory, $verze[count($verze) - 1]->getSouborClanek());
        }

        $authors = array_unique($authors,SORT_STRING);
        $authorsIds = array_unique($authorsIds,SORT_NUMERIC);

        return $this->render('home/index.html.twig',
        [
            'clanky' => $clanky,
            'datumy' => $datumy,
            'verze_clanku' => $verze_clanku,
            'soubory' => $soubory,
            'authors' =>  $authors,
            'authorsIds' =>  $authorsIds
        ]);
    }

    #[Route(path: '/create-tisk', name: 'app_create_tisk')]
    public function createTisk(Request $request, ManagerRegistry $doctrine): Response
    {   
        if ($this->getUser()==null) 
        {
            return new Response("Pristup zamitnut");
        }

        if (!in_array(Role::SEFREDAKTOR->value, $this->getUser()->getRoles()) && !in_array(Role::REDAKTOR->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }
        // Create a new empty Tisk entity
        $tisk = new Tisk();

        // Create the form for the Tisk entity
        $form = $this->createForm(TiskFormType::class, $tisk);

        // Handle the request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $tisk = $form->getData();

            if (DateTime::createFromFormat('d.m.Y', $tisk->getDatum()) == false) {
                // it's a date
                throw $this->createAccessDeniedException("Špatný formát datumu");
              }
            // Get the entity manager
            $em = $doctrine->getManager();

            // Persist the new user entity
            $em->persist($tisk);

            // Flush to save the new user entity to the database
            $em->flush();

            // Redirect to the home page or any other page
            return $this->redirectToRoute('app_home');
        }

        // Render the form view in your template
        return $this->render('home/create-tisk.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/edit-tisk/{id}', name: 'app_edit_tisk')]
    public function editTisk(Request $request, ManagerRegistry $doctrine, $id): Response
    {   
        if ($this->getUser()==null) 
        {
            return new Response("Pristup zamitnut");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }
        // Find Tisk entity
        $tisk = $doctrine->getManager()->getRepository(Tisk::class)->find($id); 

        // Create the form for the Tisk entity
        $form = $this->createForm(TiskFormType::class, $tisk);

        // Handle the request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $tisk = $form->getData();

            if (DateTime::createFromFormat('d.m.Y', $tisk->getDatum()) == false) {
                // it's a date
                throw $this->createAccessDeniedException("Špatný formát datumu");
              }
            // Get the entity manager
            $em = $doctrine->getManager();

            // Persist the new tisk entity
            $em->persist($tisk);

            // Flush to save the new tisk entity to the database
            $em->flush();

            // Redirect to the home page or any other page
            return $this->redirectToRoute('app_home');
        }

        // Render the form view in your template
        return $this->render('home/create-tisk.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/delete-tisk/{id}', name: 'app_delete_tisk')]
    public function deleteTisk(Request $request, ManagerRegistry $doctrine, $id): Response
    {   
        if ($this->getUser()==null) 
        {
            return new Response("Pristup zamitnut");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }
        // Find Tisk entity
        $tisk = $doctrine->getManager()->getRepository(Tisk::class)->find($id);
        if (!$tisk) {
            return new Response("Chyba nacitani tisku!");
        }

        if ($this->isCsrfTokenValid('delete'.$tisk->getId(), $request->request->get('_token'))) 
        {

            // Get the entity manager
            $em = $doctrine->getManager();

            // Nejdrive smazat vsechno co odkazuje na recenzni rizeni
            $rr = $em->getRepository(RecenzniRizeni::class)->findOneBy(['tisk' => $tisk->getId()]);
            if ($rr)
            {
                $clanky = $em->getRepository(Clanek::class)->findBy(['recenzni_rizeni' => $rr->getId()]);
                if ($clanky) {
                    foreach ($clanky as $clanek) {
                        $this->smazatClanek($clanek, $doctrine);
                    }
                }

                // Smazat recenzni rizeni
                $em->remove($rr);
                $em->flush();
            }

            // Remove the new tisk entity
            $em->remove($tisk);
            $em->flush();

            // Redirect to the home page or any other page
            return $this->redirectToRoute('app_home');
        }

        // Render the form view in your template
        return $this->render('home/delete-tisk.html.twig', [
            'tisk' => $tisk,
        ]);
    }


    #[Route(path: '/create-recenzni-rizeni', name: 'app_create_recenzni_rizeni')]
    public function createRecenzniRizeni(Request $request, ManagerRegistry $doctrine): Response
    {   
        if ($this->getUser()==null) 
        {
            return new Response("Pristup zamitnut");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }
        // Create a new empty Tisk entity
        $rr = new RecenzniRizeni();

        // Create the form for the Tisk entity
        $form = $this->createForm(RecenzniRizeniFormType::class, $rr);

        // Handle the request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $rr = $form->getData();

            // Get the entity manager
            $em = $doctrine->getManager();

            // Persist the new user entity
            $em->persist($rr);

            // Flush to save the new user entity to the database
            $em->flush();

            // Redirect to the home page or any other page
            return $this->redirectToRoute('app_home');
        }

        // Render the form view in your template
        return $this->render('home/create-recenzni-rizeni.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/edit-recenzni-rizeni/{id}', name: 'app_edit_recenzni_rizeni')]
    public function editRecenzniRizeni(Request $request, ManagerRegistry $doctrine, $id): Response
    {   
        if ($this->getUser()==null) 
        {
            return new Response("Pristup zamitnut");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }
        // Find Tisk entity
        $rr = $doctrine->getManager()->getRepository(RecenzniRizeni::class)->find($id); 

        // Create the form for the Tisk entity
        $form = $this->createForm(RecenzniRizeniFormType::class, $rr);

        // Handle the request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $rr = $form->getData();
            // Get the entity manager
            $em = $doctrine->getManager();

            // Persist the new tisk entity
            $em->persist($rr);

            // Flush to save the new tisk entity to the database
            $em->flush();

            // Redirect to the home page or any other page
            return $this->redirectToRoute('app_home');
        }

        // Render the form view in your template
        return $this->render('home/create-recenzni-rizeni.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/delete-recenzni-rizeni/{id}', name: 'app_delete_recenzni_rizeni')]
    public function deleteRecenzniRizeni(Request $request, ManagerRegistry $doctrine, $id): Response
    {   
        if ($this->getUser()==null) 
        {
            return new Response("Pristup zamitnut");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }
        // Find Tisk entity
        $rr = $doctrine->getManager()->getRepository(RecenzniRizeni::class)->find($id);
        if (!$rr) {
            return new Response("Chyba nacitani recenzniho rizeni!");
        }

        if ($this->isCsrfTokenValid('delete'.$rr->getId(), $request->request->get('_token'))) 
        {

            // Get the entity manager
            $em = $doctrine->getManager();

            // Nejdrive smazat vsechno co odkazuje na recenzni rizeni
            $clanky = $em->getRepository(Clanek::class)->findBy(['recenzni_rizeni' => $rr]);
            if ($clanky)
            {
                foreach ($clanky as $clanek)
                {
                    $this->smazatClanek($clanek, $doctrine);
                }
            }

            // Remove the new tisk entity
            $em->remove($rr);

            // Flush to save the new tisk entity to the database
            $em->flush();

            // Redirect to the home page or any other page
            return $this->redirectToRoute('app_home');
        }

        // Render the form view in your template
        return $this->render('home/delete-recenzni-rizeni.html.twig', [
            'rr' => $rr,
        ]);
    }

    #[Route(path: '/create-clanek', name: 'app_create_clanek')]
    public function createClanek(Request $request, ManagerRegistry $doctrine): Response
    {   
        if ($this->getUser()==null) 
        {
            return new Response("Pristup zamitnut");
        }

        if (!in_array(Role::AUTOR->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }
        // Create a new empty Clanek entity
        $clanek = new Clanek();
        $verze_clanek = new VerzeClanku();

        // Create the form for the Tisk entity
        $form = $this->createForm(ClanekFormType::class);

        // Handle the request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $soubor = $form->get('file')->getData();

            // Zkontrolovat priponu
            $extension = new UnicodeString(pathinfo($soubor->getClientOriginalName(), PATHINFO_EXTENSION));
            $ext = $extension->lower();
            if ($ext != "pdf" && $ext != "docx" && $ext != "doc") {
                return new Response("Pripona souboru musi byt .pdf nebo .doc(x)!");
            }

            $clanek->setNazevClanku($form->get('nazev_clanku')->getData());
            $clanek->setStavAutor(\App\Entity\StavAutor::PODANO->value);
            $clanek->setStavRedakce(\App\Entity\StavRedakce::NOVE_PODANY->value);
            $clanek->setUser($this->getUser());

            $recenzni_rizeni = $doctrine->getManager()->getRepository(RecenzniRizeni::class)->findAll();
            if (!$recenzni_rizeni) {
                return new Response("Momentalne neni aktivni zadne recenzni rizeni");
            }

            // Nalezeni recenzniho (prvniho a jedineho) aktivniho rizeni
            $current_time = strtotime(date('d.m.Y'));
            $valid_rc = null;
            foreach ($recenzni_rizeni as $rc)
            {
                $datum_rc = strtotime($rc->getOd());
                if ($datum_rc <= $current_time)
                {
                    $valid_rc = $rc;
                    break;
                }
            }

            if (!$valid_rc) {
                return new Response("Momentalne neni aktivni zadne recenzni rizeni");
            }
            $clanek->setRecenzniRizeni($valid_rc);

            $verze_clanek->setClanek($clanek);
            $verze_clanek->setDatumNahrani(date('d.m.Y'));
            $verze_clanek->setSouborClanek($soubor->getClientOriginalName());

            // Get the entity manager
            $em = $doctrine->getManager();

            // Persist the new user entity
            $em->persist($clanek);
            $em->persist($verze_clanek);

            // Flush to save the new user entity to the database
            $em->flush();

            // Ulozeni clanku
            {
                $public_dir = $this->getParameter('public_dir');
                $dir_path_clanek = $public_dir . '/clanky/' . $clanek->getId();
                $dir_path_verze = $dir_path_clanek . '/' . $verze_clanek->getId();

                $fs = new Filesystem();
                $fs->mkdir($dir_path_clanek);
                $fs->mkdir($dir_path_verze);
                $soubor->move($dir_path_verze, $soubor->getClientOriginalName());
            }

            // Redirect to the home page or any other page
            return $this->redirectToRoute('app_author_articles_overview');
        }

        // Render the form view in your template
        return $this->render('home/create-clanek.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/create-verze-clanek/{clanek_id}', name: 'app_create_verze_clanek')]
    public function createVerzeClanek(Request $request, ManagerRegistry $doctrine, $clanek_id): Response
    {
        if ($this->getUser() == null) {
            return new Response("Pristup zamitnut");
        }

        if (!in_array(Role::AUTOR->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }

        $em = $doctrine->getManager();
        $clanek = $em->getRepository(Clanek::class)->findOneBy(['id' => $clanek_id]);
        if (!$clanek) {
            return new Response("Chyba nacitani clanku!");
        }
        $verze_clanek = new VerzeClanku();

        $form = $this->createForm(VerzeClanekFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $soubor = $form->get('file')->getData();
            if (!$soubor) {
                return new Response("Chyba nacitani odeslaneho souboru!");
            }

            $verze_clanek->setClanek($clanek);
            $verze_clanek->setDatumNahrani(date('d.m.Y'));
            $verze_clanek->setSouborClanek($soubor->getClientOriginalName());

            $em->persist($verze_clanek);
            $em->flush();

            // Ulozeni clanku
            {
                $public_dir = $this->getParameter('public_dir');
                $dir_path_clanek = $public_dir . '/clanky/' . $clanek->getId();
                $dir_path_verze = $dir_path_clanek . '/' . $verze_clanek->getId();

                $fs = new Filesystem();
                $fs->mkdir($dir_path_verze);
                $soubor->move($dir_path_verze, $soubor->getClientOriginalName());
            }

            return $this->redirectToRoute('app_author_article_detail', ['clanek_id' => $clanek->getId()]);
        }

        return $this->render('home/create-clanek-verze.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Smazani vsech radku v ostatnich entitach, ktere odkazuji na konkretni clanek
    private function smazatClanek(Clanek &$clanek, ManagerRegistry &$doctrine): void
    {
        $em = $doctrine->getManager();

        // Smazani posudku clanku
        $posudky = $em->getRepository(Posudek::class)->findBy(['clanek' => $clanek->getId()]);
        if ($posudky)
        {
            foreach ($posudky as $posudek)
            {
                $em->remove($posudek);
                $em->flush();
            }
        }

        // Smazani namitky
        $namitka = $em->getRepository(Namitka::class)->findOneBy(['clanek' => $clanek->getId()]);
        if ($namitka)
        {
            $em->remove($namitka);
            $em->flush();
        }

        // Smazani komentaru k ukolu a samotny ukol
        $ukol = $em->getRepository(Ukol::class)->findOneBy(['clanek' => $clanek->getId()]);
        if ($ukol)
        {
            $em->remove($ukol);
            $em->flush();
        }

        // Smazani komentaru clanku a verzi clanku
        $verze_clanku = $em->getRepository(VerzeClanku::class)->findBy(['clanek' => $clanek->getId()]);
        if ($verze_clanku)
        {
            foreach ($verze_clanku as $vc)
            {
                // Nacist komentare k dane verzi clanku a smazat
                $komentare_clanek = $em->getRepository(KomentarClanek::class)->findBy(['verze_clanku' => $vc->getId()]);
                if ($komentare_clanek)
                {
                    foreach ($komentare_clanek as $kc)
                    {
                        $em->remove($kc);
                        $em->flush();
                    }
                }

                $komentare = $em->getRepository(KomentarUkol::class)->findBy(['verze_clanku' => $vc->getId()]);
                if ($komentare)
                {
                    foreach ($komentare as $kc)
                    {
                        $em->remove($kc);
                        $em->flush();
                    }
                }

                $em->remove($vc);
                $em->flush();
            }
        }

        // Smazani clanku (souboru) ulozeneho na serveru
        {
            $path = $this->getParameter('public_dir') . '/clanky/' . $clanek->getId();
            $fs = new Filesystem();
            $fs->remove($path);
        }

        $em->remove($clanek);
        $em->flush();
    }

    #[Route(path: '/delete-clanek/{id}', name: 'app_delete_clanek')]
    public function deleteClanek(Request $request, ManagerRegistry $doctrine, $id): Response
    {   
        if ($this->getUser()==null) 
        {
            return new Response("Pristup zamitnut");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles()) &&
            !in_array(Role::AUTOR->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }

        // Create a new empty Clanek entity
        $clanek = $doctrine->getManager()->getRepository(Clanek::class)->find($id);
        if (!$clanek) {
            return new Response("Chyba nacitani clanku!");
        }

        if ($this->isCsrfTokenValid('delete'.$clanek->getId(), $request->request->get('_token'))) 
        {
            // Get the entity manager
            $em = $doctrine->getManager();

            // Smazani vsech radku v ostatnich entitach, ktere odkazuji na konkretni clanek
            $this->smazatClanek($clanek, $doctrine);
            $em->remove($clanek);
            $em->flush();

            // Redirect to the home page or any other page
            return $this->redirectToRoute('app_home');
        }

        // Render the form view in your template
        return $this->render('home/delete-clanek.html.twig', [
            'clanek' => $clanek,
        ]);
    }

    #[Route(path: '/delete-user/{id}', name: 'app_delete_user')]
    public function deleteUser(Request $request, ManagerRegistry $doctrine, $id): Response
    {   
        if ($this->getUser()==null) 
        {
            return new Response("Pristup zamitnut");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }
        // Find User entity
        $user = $doctrine->getManager()->getRepository(User::class)->find($id);
        if (!$user) {
            return new Response("Uživatel nenalezen!");
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) 
        {

            // Get the entity manager
            $em = $doctrine->getManager();
            
            // Nejdrive smazat vsechno co odkazuje na uzivatele
            $clanky = $em->getRepository(Clanek::class)->findBy(['user' => $user->getId()]);
            if ($clanky)
            {
                foreach ($clanky as $clanek)
                {
                    $this->smazatClanek($clanek, $doctrine);
                }
            }
            
            $em->remove($user);
            $em->flush();

            // Redirect to the home page or any other page
            return $this->redirectToRoute('app_home');
        }

        // Render the form view in your template
        return $this->render('home/delete-user.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route(path: '/manage-content', name: 'app_manage_content')]
    public function manageContent(): Response
    {
        if ($this->getUser()==null) {
            return new Response("Pristup zamitnut");
        }
        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }
        return $this->render('home/manage-content.html.twig');
    }

    #[Route(path: '/tisk-overview', name: 'app_tisk_overview')]
    public function tiskOverview(ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()==null) {
            return new Response("Pristup zamitnut");
        }
        if (!in_array(Role::SEFREDAKTOR->value, $this->getUser()->getRoles()) &&
            !in_array(Role::REDAKTOR->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }
        $tisky = $doctrine->getManager()->getRepository(Tisk::class)->findAll();
        return $this->render('home/tisk-overview.html.twig', ['tisky' => $tisky]);
    }

    #[Route(path: '/recenzni-rizeni-overview', name: 'app_recenzni_rizeni_overview')]
    public function recenzniRizeniOverview(ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()==null) {
            return new Response("Pristup zamitnut");
        }
        if (!in_array(Role::SEFREDAKTOR->value, $this->getUser()->getRoles()) &&
            !in_array(Role::REDAKTOR->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }
        $recenzni_rizeni = $doctrine->getManager()->getRepository(RecenzniRizeni::class)->findAll();
        return $this->render('home/recenzni-rizeni-overview.html.twig', ['recenzni_rizeni' => $recenzni_rizeni]);
    }

    #[Route(path: '/clanky-overview', name: 'app_clanky_overview')]
    public function clankyOverview(ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()==null) {
            return new Response("Pristup zamitnut");
        }
        if (!in_array(Role::SEFREDAKTOR->value, $this->getUser()->getRoles()) &&
            !in_array(Role::REDAKTOR->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }

        $clanky= $doctrine->getManager()->getRepository(Clanek::class)->findAll();
        return $this->render('home/clanky-overview.html.twig', ['clanky' => $clanky]);
    }

    #[Route(path: '/create-ukol', name: 'app_create_task')]
    public function createTask(Request $request, ManagerRegistry $doctrine): Response
    {   
        if ($this->getUser() == null ||
            (!in_array(Role::SEFREDAKTOR->value, $this->getUser()->getRoles()) &&
            !in_array(Role::REDAKTOR->value, $this->getUser()->getRoles()))) {
            return new Response("Pristup zamitnut");
        }

        $ukol = new Ukol();
        $form = $this->createForm(UkolFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $doctrine->getManager();
            $clanek = $form->get('clanek')->getData();
            if (!$clanek) {
                return new Response("Musite vybrat clanek pro hodnoceni!");
            }

            $clanek->setStavRedakce(\App\Entity\StavRedakce::CEKA_NA_STANOVENI_RECENZENTU->value);
            $clanek->setStavAutor(\App\Entity\StavAutor::PREDANO_RECENZENTUM->value);

            $ukol->setDeadline($form->get('deadline')->getData());
            $ukol->setClanek($clanek);
            $ukol->setUser($form->get('user')->getData());

            $em->persist($ukol);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/create-ukol.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/delete-ukol/{id}', name: 'app_delete_ukol')]
    public function deleteUkol(Request $request, ManagerRegistry $doctrine, $id): Response
    {   
        if ($this->getUser()==null) 
        {
            return new Response("Pristup zamitnut");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }

        $ukol = $doctrine->getManager()->getRepository(Ukol::class)->find($id);
        if (!$ukol) {
            return new Response("Chyba nacitani tisku!");
        }

        if ($this->isCsrfTokenValid('delete'.$ukol->getId(), $request->request->get('_token'))) 
        {

            // Get the entity manager
            $em = $doctrine->getManager();

            $em->remove($ukol);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        // Render the form view in your template
        return $this->render('home/delete-ukol.html.twig', [
            'ukol' => $ukol,
        ]);
    }

    /*
    #[Route(path: '/edit-ukol/{id}', name: 'app_edit_ukol')]
    public function editUkol(ManagerRegistry $doctrine,$id): Response
    {
        $ukoly= $doctrine->getManager()->getRepository(Ukol::class)->findAll();

        return $this->render('home/ukoly-overview.html.twig', ['ukoly' => $ukoly]);
    }*/

    #[Route(path: '/ukoly-overview', name: 'app_ukoly_overview')]
    public function ukolyOverview(ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()==null)
        {
            return new Response("Pristup zamitnut");
        }

        if (!in_array(Role::REDAKTOR->value, $this->getUser()->getRoles()) &&
            !in_array(Role::SEFREDAKTOR->value, $this->getUser()->getRoles()))
        {
            return new Response("Pristup zamitnut");
        }

        // Najdu vsechny ukoly
        $ukoly = $doctrine->getManager()->getRepository(Ukol::class)->findAll();
        $stavy = array();
        $clanky = array();
        $current_date = strtotime(date('d.m.Y'));
        foreach ($ukoly as $ukol)
        {
            $clanek = $doctrine->getManager()->getRepository(Clanek::class)->findOneBy(['id' => $ukol->getClanek()->getId()]);
            array_push($clanky, $clanek);

            // Zjistit zda je ukol stale aktivni nebo ne
            if ($current_date > strtotime($ukol->getDeadline())) {
                array_push($stavy, "Neaktivni");
            }
            else {
                array_push($stavy, "Aktivni");
            }
        }

        $ukoly = array_reverse($ukoly);
        $stavy = array_reverse($stavy);
        $clanky = array_reverse($clanky);

        return $this->render('home/ukoly-overview.html.twig',
            [
                'ukoly' => $ukoly,
                'clanky' => $clanky,
                'stavy' => $stavy
            ]);
    }

    #[Route(path: '/recenzent-ukoly-overview', name: 'app_recenzent_ukoly_overview')]
    public function recenzentUkolyOverview(ManagerRegistry $doctrine): Response
    {
        if ($this->getUser() == null || !in_array(Role::RECENZENT->value, $this->getUser()->getRoles()))
        {
            return new Response("Pristup zamitnut");
        }

        $user = $doctrine->getManager()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $ukoly = $doctrine->getManager()->getRepository(Ukol::class)->findBy(['user' => $user->getId()]);
        $clanky = array();
        foreach ($ukoly as $ukol)
        {
            $clanek = $doctrine->getManager()->getRepository(Clanek::class)->findOneBy(['id' => $ukol->getClanek()->getId()]);
            array_push($clanky, $clanek);
        }

        return $this->render('home/recenzent-ukoly-overview.html.twig',
            [
                'ukoly' => $ukoly,
                'clanky' => $clanky
            ]);
    }

    // Zaslani posudku
    #[Route(path: '/posudek/{clanek_id}', name: 'app_posudek')]
    public function posudek(Request $request, ManagerRegistry $doctrine, $clanek_id): Response
    {
        if ($this->getUser()==null)
        {
            return new Response("Pristup zamitnut");
        }

        if (!in_array(Role::RECENZENT->value, $this->getUser()->getRoles())) {
            return new Response("Pristup zamitnut");
        }

        $form = $this->createForm(PosudekType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $doctrine->getManager();

            $aktualnost = $form->get('aktualnost')->getData();
            $originalita = $form->get('originalita')->getData();
            $odbornaUroven = $form->get('odbornaUroven')->getData();
            $jazykovaUroven = $form->get('jazykovaUroven')->getData();
            $soubor = $form->get('file')->getData();

            $posudek = new Posudek();
            $posudek->setAktualnost($aktualnost);
            $posudek->setOriginalita($originalita);
            $posudek->setOdbornaUroven($odbornaUroven);
            $posudek->setJazykovaUroven($jazykovaUroven);
            $posudek->setPosudekSoubor($soubor->getClientOriginalName());
            $posudek->setClanek($em->getRepository(Clanek::class)->findOneBy(['id' => $clanek_id]));
            $posudek->setUser($em->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]));

            $em->persist($posudek);
            $em->flush();

            // Ulozeni souboru (posudku)
            {
                $public_dir = $this->getParameter('public_dir');
                $dir_path = $public_dir . '/posudky/' . $posudek->getId();

                $fs = new Filesystem();
                $fs->mkdir($dir_path);
                $soubor->move($dir_path, $soubor->getClientOriginalName());
            }

            return $this->redirectToRoute('app_recenzent_ukoly_overview');
        }

        return $this->render('home/test-hodnoceni.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route(path: '/rules', name: 'app_rules')]
    public function rules(Request $request, ManagerRegistry $doctrine): Response
    {
        return $this->render('home/rules.html.twig');
    }
}
