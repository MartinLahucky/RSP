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
use DateTime;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;


class HomeController extends AbstractController

{
    #[Route('/home', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $clanky = $manager->getRepository(Clanek::class)->findBy(['stav_autor' => \App\Entity\StavAutor::PRIJATO]);
        if (!$clanky) {
            return new Response("Žádné články k zobrazení");
        }

        // Nacist datumy vydani (tisku) a soubory clanku
        $datumy = array();
        $verze_clanku = array();
        $soubory = array();
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

            array_push($datumy, $tisk->getDatum());
            array_push($verze_clanku, $verze[count($verze) - 1]);
            array_push($soubory, $verze[count($verze) - 1]->getSouborClanek());
        }

        return $this->render('home/index.html.twig',
        [
            'clanky' => $clanky,
            'datumy' => $datumy,
            'verze_clanku' => $verze_clanku,
            'soubory' => $soubory,
        ]);
    }

    #[Route(path: '/create-tisk', name: 'app_create_tisk')]
    public function createTisk(Request $request, ManagerRegistry $doctrine): Response
    {   
        if ($this->getUser()==null) 
        {
            return new Response("Pristup zamitnut");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
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
            return new Response("Chyba nacitani tisku");
        }

        if ($this->isCsrfTokenValid('delete'.$tisk->getId(), $request->request->get('_token'))) 
        {

            // Get the entity manager
            $em = $doctrine->getManager();

            // Nejdrive smazat vsechno co odkazuje na recenzni rizeni
            $rr = $em->getRepository(RecenzniRizeni::class)->findBy(['tisk' => $tisk]);
            if (!$rr) {
                return new Response("Chyba nacitani recenzniho rizeni");
            }

            $clanky = $em->getRepository(Clanek::class)->findBy(['recenzni_rizeni' => $rr]);
            if ($clanky)
            {
                foreach ($clanky as $clanek)
                {
                    $this->smazatClanek($clanek, $doctrine);
                }
            }

            // Remove the new tisk entity
            $em->remove($tisk);

            // Flush to save the new tisk entity to the database
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

    #[Route(path: '/delete-recenzni-rizeni/{id}', name: 'app_delete_recenzni_rizeni')] //TODO - fix
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

            $clanek->setNazevClanku($form->get('nazev_clanku')->getData());
            $clanek->setStavAutor(\App\Entity\StavAutor::PODANO->value);
            $clanek->setStavRedakce(\App\Entity\StavRedakce::NOVE_PODANY->value);
            $clanek->setUser($this->getUser());

            $recenzni_rizeni = $doctrine->getManager()->getRepository(RecenzniRizeni::class)->findAll();
            if (!$recenzni_rizeni) {
                return new Response("Momentalne neni aktivni zadne recenzni rizeni");
            }

            // Nalezeni recenzniho (prvniho a jedineho) aktivniho rizeni
            // TODO: Mozna by se dalo zjenodusit tim, ze by se v DB vytvoril novy atribut, ktery by urcoval zda je dane recenzni rizeni jiz uzavreno nebo ne?????
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
            $verze_clanek->setZpristupnenRecenzentum(false);

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
            return $this->redirectToRoute('app_home');
        }

        // Render the form view in your template
        return $this->render('home/create-clanek.html.twig', [
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
            }
        }

        // Smazani namitky
        $namitka = $em->getRepository(Namitka::class)->findOneBy(['clanek' => $clanek->getId()]);
        if ($namitka)
        {
            $em->remove($namitka);
        }

        // Smazani komentaru k ukolu a samotny ukol
        $ukol = $em->getRepository(Ukol::class)->findOneBy(['clanek' => $clanek->getId()]);
        if ($ukol)
        {
            // Nacist vsechny komentare k ukolu
            $komentare_ukol = $em->getRepository(KomentarUkol::class)->findBy(['ukol' => $ukol->getId()]);
            if ($komentare_ukol)
            {
                foreach ($komentare_ukol as $ku)
                {
                    $em->remove($ku);
                }
            }

            $em->remove($ukol);
        }

        // TODO: Kdyz nemam komentare tak funguje mazani verzi ale kdyz mam komentare tak nejde????
        // Smazani komentaru clanku a verzi clanku
        $verze_clanku = $em->getRepository(VerzeClanku::class)->findBy(['clanek' => $clanek->getId()]);
        if ($verze_clanku)
        {
            foreach ($verze_clanku as $vc)
            {
                // Nacist komentare k dane verzi clanku
                $komentare_clanek = $em->getRepository(KomentarClanek::class)->findBy(['verze_clanku' => $vc->getId()]);
                if ($komentare_clanek)
                {
                    foreach ($komentare_clanek as $kc)
                    {
                        $em->remove($kc);
                    }
                }

                $em->remove($vc);
            }
        }

        // Smazani clanku (souboru) ulozeneho na serveru
        {
            $path = $this->getParameter('public_dir') . '/clanky/' . $clanek->getId();
            $fs = new Filesystem();
            $fs->remove($path);
        }

        $em->remove($clanek);
    }

    #[Route(path: '/delete-clanek/{id}', name: 'app_delete_clanek')]
    public function deleteClanek(Request $request, ManagerRegistry $doctrine, $id): Response
    {   
        if ($this->getUser()==null) 
        {
            return new Response("Pristup zamitnut");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
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
            $em->flush();

            // Redirect to the home page or any other page
            return $this->redirectToRoute('app_home');
        }

        // Render the form view in your template
        return $this->render('home/delete-clanek.html.twig', [
            'clanek' => $clanek,
        ]);
    }
}
