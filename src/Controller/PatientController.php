<?php

namespace App\Controller;

use App\Entity\Rendezvous;
use App\Form\PatientRendezvousType;
use App\Repository\RendezvousRepository;
use App\Repository\ProfessionalsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PatientController extends AbstractController
{
    #[Route('/patient/dashboard', name: 'patient_dashboard')]
    public function dashboard(
        RendezvousRepository $rendezvousRepository,
        ProfessionalsRepository $professionalsRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_PATIENT');

        // Fetch logged-in patient and their rendezvous
        $patient = $this->getUser()->getPatient();
        $rendezvousList = $rendezvousRepository->findBy(['patient' => $patient]);

        // Handle creating a new rendezvous
        $newRendezvous = new Rendezvous();
        $form = $this->createForm(PatientRendezvousType::class, $newRendezvous, [
            'professionals' => $professionalsRepository->findAll(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newRendezvous->setPatient($patient);
            $newRendezvous->setStatus('scheduled');
            $entityManager->persist($newRendezvous);
            $entityManager->flush();

            $this->addFlash('success', 'Votre rendez-vous a été créé avec succès.');
            return $this->redirectToRoute('patient_dashboard');
        }

        return $this->render('patient/dashboard.html.twig', [
            'rendezvousList' => $rendezvousList,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/patient/rendezvous/{id}/delete', name: 'patient_rendezvous_delete')]
    public function delete(Rendezvous $rendezvous, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PATIENT');

        if ($rendezvous->getPatient() !== $this->getUser()->getPatient() || $rendezvous->getStatus() !== 'scheduled') {
            throw $this->createAccessDeniedException();
        }

        $entityManager  ->remove($rendezvous);
        $entityManager->flush();

        $this->addFlash('success', 'Rendez-vous supprimé avec succès.');
        return $this->redirectToRoute('patient_dashboard');
    }
}
