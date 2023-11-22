<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\UserRolesFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Role\Role as RoleRole;

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
    


}
