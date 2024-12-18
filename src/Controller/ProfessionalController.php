<?php

namespace App\Controller;

use App\Entity\Rendezvous;
use App\Form\RendezvousType;
use App\Repository\RendezvousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfessionalController extends AbstractController
{
    #[Route('/professional/dashboard', name: 'professional_dashboard')]
    public function dashboard(
        RendezvousRepository $rendezvousRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_PROFESSIONAL');
        $professional = $this->getUser()->getProfessional();

        // Fetch all rendezvous for the logged-in professional
        $rendezvousList = $rendezvousRepository->findBy(['professional' => $professional]);

        // Calculate statistics
        $totalRendezvous = count($rendezvousList);
        $statusCounts = [
            'scheduled' => 0,
            'completed' => 0,
            'canceled' => 0,
        ];
        
        foreach ($rendezvousList as $rendezvous) {
            $status = $rendezvous->getNormalizedStatus(); // Use normalized status
            if (isset($statusCounts[$status])) {
                $statusCounts[$status]++;
            }
        }

        // Find the next upcoming rendezvous
        $nextRendezvous = $rendezvousRepository->createQueryBuilder('r')
            ->where('r.professional = :professional')
            ->andWhere('r.appointmentDate > :now')
            ->setParameter('professional', $professional)
            ->setParameter('now', new \DateTime())
            ->orderBy('r.appointmentDate', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        // Handle creating a new Rendezvous
        $newRendezvous = new Rendezvous();
        $newForm = $this->createForm(RendezvousType::class, $newRendezvous);
        $newForm->handleRequest($request);

        if ($newForm->isSubmitted() && $newForm->isValid()) {
            $newRendezvous->setProfessional($professional);
            $entityManager->persist($newRendezvous);
            $entityManager->flush();

            $this->addFlash('success', 'Le rendez-vous a été créé avec succès.');
            return $this->redirectToRoute('professional_dashboard');
        }
        // Handle editing an existing Rendezvous
        $editRendezvous = null;
        $editForm = null;

        if ($request->query->has('edit')) {
            $editRendezvousId = $request->query->get('edit');
            $editRendezvous = $rendezvousRepository->find($editRendezvousId);

            // Ensure the Rendezvous belongs to the logged-in professional
            if ($editRendezvous && $editRendezvous->getProfessional() === $professional) {
                $editForm = $this->createForm(RendezvousType::class, $editRendezvous);
                $editForm->handleRequest($request);

                if ($editForm->isSubmitted() && $editForm->isValid()) {
                    $entityManager->flush(); // Save changes without persisting again
                    $this->addFlash('success', 'Le rendez-vous a été modifié avec succès.');
                    return $this->redirectToRoute('professional_dashboard');
                }
            }
        }

        return $this->render('professional/dashboard.html.twig', [
            'user' => $this->getUser(),
            'rendezvousList' => $rendezvousList,
            'newForm' => $newForm->createView(),
            'editForm' => $editForm ? $editForm->createView() : null,
            'editRendezvous' => $editRendezvous,
            'statusCounts' => $statusCounts,
            'totalRendezvous' => $totalRendezvous,
            'nextRendezvous' => $nextRendezvous,
        ]);
    }

    #[Route('/rendezvous/{id}/delete', name: 'rendezvous_delete', methods: ['POST'])]
    public function deleteRendezvous(Rendezvous $rendezvous, EntityManagerInterface $entityManager): Response
    {
        // Ensure the logged-in user can only delete their own rendezvous
        $professional = $this->getUser()->getProfessional();

        if ($rendezvous->getProfessional() !== $professional) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer ce rendez-vous.');
        }

        $entityManager->remove($rendezvous);
        $entityManager->flush();

        $this->addFlash('success', 'Le rendez-vous a été supprimé avec succès.');
        return $this->redirectToRoute('professional_dashboard');
    }
    
}
