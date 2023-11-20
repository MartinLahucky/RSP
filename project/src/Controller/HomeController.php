<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

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
}
