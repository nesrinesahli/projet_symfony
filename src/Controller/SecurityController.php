<?php

// src/Controller/SecurityController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    // Inject the EntityManagerInterface into the controller
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // Get the user type from the request
        $userType = $request->query->get('userType', 'professional'); // Default to 'professional'

        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'userType' => $userType,
        ]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        // Debugging: check if form is submitted and valid
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // Debugging: Display form data
                dump($user); // Will dump the $user object to the browser console/log
            } else {
                dump($form->getErrors(true)); // Show validation errors if form is not valid
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));

            // Ensure the Patient relationship is correctly set
            $patient = $user->getPatient();
            $patient->setUser($user);

            // Persist the user and patient entities
            $entityManager->persist($user);
            $entityManager->persist($patient);
            $entityManager->flush();

            // Flash message
            $this->addFlash('success', 'Vous êtes bien enregistré');

            // Redirect to login page
            return $this->redirectToRoute('login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


}
