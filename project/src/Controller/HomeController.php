<?php

namespace App\Controller;

use App\Entity\Clanek;
use App\Entity\RecenzniRizeni;
use App\Entity\Role;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\Persistence\ManagerRegistry;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Kernel',
        ]);
    }

    #[Route('/send-email', name: 'email_sender')]
    public function emailSend(MailerInterface $mailer):Response
    {
        $email = (new Email())
        ->to('foxisfox150@gmail.com')
        ->from('kernel@email.cz')
        ->subject('Vítejte')
        ->text('Test zpráva xd');

        $mailer->send($email);

        return $this->redirectToRoute('app_home');
    }

    #[Route('/createClanek', name: 'app_create_clanek')] //Dodělat xD OmehaLuL
    public function createClanek(Request $request, ManagerRegistry $doctrine):Response
    {
        if ($this->getUser()==null) 
        {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }

        if (!in_array(Role::ADMIN->value, $this->getUser()->getRoles())) 
        {
            throw $this->createAccessDeniedException("lol nemáš práva xD");
        }

        $clanek = new Clanek(); 
        //Každý článěk bude v intervalu pro určité recenzní řízení, které bude představovat množinu, do které se budou řadit články, schválené články se zařadí do tisku
        //Prvně se vytvoří tisk o určité kapacitě, pro daný tisk se vytvoří rezenzní řízení do kterého se budou řadit články vytvořené a schálené v daném intervalu rezenzního řízení a následně se to přesune do tisku
        $form = $this->createForm(ClanekFormType::class, $clanek);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $clanek = $form->getData();

            $em = $doctrine->getManager();

            $em->persist($clanek);

            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        // Render the form view in your template
        return $this->render('home/create-clanek.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
