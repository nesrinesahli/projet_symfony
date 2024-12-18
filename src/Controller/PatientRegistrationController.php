<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Patient;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PatientRegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        // Initialize the form
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Extract individual form field data
            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();
            $firstName = $form->get('first_name')->getData();
            $lastName = $form->get('last_name')->getData();
            $gender = $form->get('gender')->getData();
            $dateOfBirth = $form->get('date_of_birth')->getData();
            $phoneNumber = $form->get('phone_number')->getData();
            $chronicDisease = $form->get('chronic_disease')->getData();

            // Create and configure the User entity
            $user = new User();
            $user->setRole('ROLE_PATIENT');
            $user->setEmail($email);
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            // Persist User
            $entityManager->persist($user);
            $entityManager->flush();

            // Create and configure the Patient entity
            $patient = new Patient();
            $patient->setUser($user); // Link User object to Patient
            $patient->setFirstName($firstName);
            $patient->setLastName($lastName);
            $patient->setGender($gender);
            $patient->setDateOfBirth($dateOfBirth);
            $patient->setPhoneNumber($phoneNumber);
            $patient->setChronicDisease($chronicDisease ?? false);
            $patient->setCreatedAt(new \DateTime());

            // Persist Patient
            $entityManager->persist($patient);
            $entityManager->flush();

            // Add success message and redirect
            $this->addFlash('success', 'Votre compte a été créé avec succès!');
            return $this->redirectToRoute('login');
        }

        // Render the registration form
        return $this->render('register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
