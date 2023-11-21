<?php

namespace App\Controller;


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

    #[Route('/test-email', name: 'email_test')]
    public function testEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->to('postmaster@martin-lahucky.com')
            ->from('kernel@email.cz')
            ->subject('Testovací e-mail')
            ->text('Toto je testovací e-mail.');

        $mailer->send($email);

        return new Response('Testovací e-mail byl odeslán.');
    }

}
