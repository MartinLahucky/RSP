<?php

namespace App\Controller;

use App\Entity\Clanek;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Tisk;
use App\Entity\RecenzniRizeni;
use App\Form\ClanekFormType;
use App\Form\RecenzniRizeniFormType;
use App\Form\TiskFormType;
use DateTime;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Kernel',
        ]);
    }

    #[Route(path: '/create-tisk', name: 'app_create_tisk')]
    public function createTisk(Request $request, ManagerRegistry $doctrine): Response
    {   
        if ($this->getUser()==null) 
        {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
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
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
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
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }
        // Find Tisk entity
        $tisk = $doctrine->getManager()->getRepository(Tisk::class)->find($id); 

        if ($this->isCsrfTokenValid('delete'.$tisk->getId(), $request->request->get('_token'))) 
        {

            // Get the entity manager
            $em = $doctrine->getManager();

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
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
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
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
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
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }
        // Find Tisk entity
        $rr = $doctrine->getManager()->getRepository(RecenzniRizeni::class)->find($id); 

        if ($this->isCsrfTokenValid('delete'.$rr->getId(), $request->request->get('_token'))) 
        {

            // Get the entity manager
            $em = $doctrine->getManager();

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
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }
        // Create a new empty Clanek entity
        $clanek = new Clanek();

        // Create the form for the Tisk entity
        $form = $this->createForm(ClanekFormType::class, $clanek);

        // Handle the request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $clanek = $form->getData();

            // Get the entity manager
            $em = $doctrine->getManager();

            // Persist the new user entity
            $em->persist($clanek);

            // Flush to save the new user entity to the database
            $em->flush();

            // Redirect to the home page or any other page
            return $this->redirectToRoute('app_home');
        }

        // Render the form view in your template
        return $this->render('home/create-clanek.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
