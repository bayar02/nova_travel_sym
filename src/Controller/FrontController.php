<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ReclamationRepository;
use App\Repository\HebergementRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Reclamation;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Hebergement;
use App\Entity\ReservationHebergement;
use App\Form\ReclamationType;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ReservationHebergementType;


final class FrontController extends AbstractController
{


    #[Route('/reclamations', name: 'reclamation_list', methods: ['GET'])]
    public function getAllReclamations(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('Front/ReclamationList.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }


    #[Route('/newR', name: 'add_reclamation', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 🔥 Manually fetch the user with ID = 1 and assign it
            $user = $entityManager->getRepository(User::class)->find(1);
            $reclamation->setUser($user);

            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('reclamation_list');
        }

        return $this->render('Front/addReclamation.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }


    #[Route('/hebergements', name: 'hebergement_list', methods: ['GET'])]
    public function getAllHebergements(HebergementRepository $repo): Response
    {
        return $this->render('Front/HebergementList.html.twig', [
            'hebergements' => $repo->findAll(),
        ]);
    }

    #[Route('details/{id}', name: 'hebergementDetails', methods: ['GET'])]
    public function show(Hebergement $hebergement): Response
    {
        return $this->render('Front/hebergementDetails.html.twig', [
            'hebergement' => $hebergement,
        ]);
    }

    #[Route('/newHebergement/{id}', name: 'reservationH', methods: ['GET', 'POST'])]
public function newHebergement(Request $request, EntityManagerInterface $entityManager, int $id): Response
{
    $hebergement = $entityManager->getRepository(Hebergement::class)->find($id);
    if (!$hebergement) {
        throw $this->createNotFoundException('Hébergement non trouvé.');
    }

    $reservationHebergement = new ReservationHebergement();
    $reservationHebergement->setHebergement($hebergement); // pre-fill with selected hebergement

    $form = $this->createForm(ReservationHebergementType::class, $reservationHebergement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user = $entityManager->getRepository(User::class)->find(1); // or get from session/token
        $reservationHebergement->setUser($user); // link user if needed

        $entityManager->persist($reservationHebergement);
        $entityManager->flush();

        return $this->redirectToRoute('hebergement_list', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('Front/addReservation.html.twig', [
        'reservation_hebergement' => $reservationHebergement,
        'form' => $form->createView(),
    ]);
}

}
