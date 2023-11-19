<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRolesFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
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

        // Check if the user has the right to edit this role 'ROLE_ADMIN'
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Nemůžete editovat tuto roli.');
        }

        $form = $this->createForm(UserRolesFormType::class, $role);  //Tvorba nového formuléře podle vzoru ProductFormType

        $form->handleRequest($request);  //Předání dat z formuláře

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            if (!empty($password)) {
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
}
