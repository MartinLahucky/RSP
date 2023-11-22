<?php

namespace App\Controller;


use App\Entity\Clanek;
use App\Entity\RecenzniRizeni;
use App\Entity\Tisk;
use App\Entity\VerzeClanku;
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
    public function index(ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $clanky = $manager->getRepository(Clanek::class)->findBy(['stav_autor' => \App\Entity\StavAutor::PRIJATO]);
        if (!$clanky) {
            return new Response("Žádné články k zobrazení");
        }

        // Nacist datumy vydani (tisku) a soubory clanku
        $datumy = array();
        $soubory = array();
        foreach ($clanky as $clanek)
        {
            $recenzni_rizeni = $manager->getRepository(RecenzniRizeni::class)->findOneBy(['id' => $clanek->getIdRecenzniRizeni()]);
            if (!$recenzni_rizeni) {
                return new Response("Chyba načítání článků");
            }

            $tisk = $manager->getRepository(Tisk::class)->findOneBy(['id' => $recenzni_rizeni->getTisk()]);
            if (!$tisk) {
                return new Response("Chyba načítání článků");
            }

            $verze_clanku =
                $manager->getRepository(VerzeClanku::class)->findBy(['clanek' => $clanek->getId()]);
            if (!$verze_clanku) {
                return new Response("Chyba načítání článků");
            }

            array_push($datumy, $tisk->getDatum());
            array_push($soubory, $verze_clanku[count($verze_clanku) - 1]->getSouborClanek());
        }

        return $this->render('home/index.html.twig',
        [
            'clanky' => $clanky,
            'datumy' => $datumy,
            'soubory' => $soubory,
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
