<?php

namespace App\Controller;

use App\Entity\ReservationVol;
<<<<<<< HEAD
use App\Entity\Vol;
use App\Form\ReservationVolType;
use App\Repository\ReservationVolRepository;
use App\Repository\VolRepository;
=======
use App\Form\ReservationVolType;
use App\Repository\ReservationVolRepository;
>>>>>>> f5842df (Initial commit for Events branch)
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
<<<<<<< HEAD
use Symfony\Component\Security\Http\Attribute\IsGranted;
=======
>>>>>>> f5842df (Initial commit for Events branch)

#[Route('/reservation/vol')]
final class ReservationVolController extends AbstractController
{
    #[Route(name: 'app_reservation_vol_index', methods: ['GET'])]
    public function index(ReservationVolRepository $reservationVolRepository): Response
    {
        return $this->render('reservation_vol/index.html.twig', [
            'reservation_vols' => $reservationVolRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_vol_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationVol = new ReservationVol();
        $form = $this->createForm(ReservationVolType::class, $reservationVol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationVol);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_vol_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation_vol/new.html.twig', [
            'reservation_vol' => $reservationVol,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_vol_show', methods: ['GET'])]
    public function show(ReservationVol $reservationVol): Response
    {
        return $this->render('reservation_vol/show.html.twig', [
            'reservation_vol' => $reservationVol,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_vol_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationVol $reservationVol, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationVolType::class, $reservationVol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_vol_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation_vol/edit.html.twig', [
            'reservation_vol' => $reservationVol,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_vol_delete', methods: ['POST'])]
    public function delete(Request $request, ReservationVol $reservationVol, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationVol->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservationVol);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_vol_index', [], Response::HTTP_SEE_OTHER);
    }
<<<<<<< HEAD

    #[Route('/user/flights', name: 'app_user_flights', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function userFlights(ReservationVolRepository $reservationVolRepository): Response
    {
        $user = $this->getUser();
        $reservations = $reservationVolRepository->findByUserWithFlightDetails($user);

        return $this->render('reservation_vol/user_flights.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/book/{id}', name: 'app_reservation_vol_book', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function bookFlight(Request $request, Vol $vol, EntityManagerInterface $entityManager): Response
    {
        // Create a new reservation
        $reservation = new ReservationVol();
        $reservation->setVol($vol);
        $reservation->setUser($this->getUser());
        
        // Default values
        $reservation->setClasse('Economy');
        $reservation->setNbBillets(1);
        
        // Handle form submission
        if ($request->isMethod('POST')) {
            $classe = $request->request->get('classe');
            $nbBillets = (int)$request->request->get('nb_billets');
            
            // Basic validation
            if ($classe && in_array($classe, ['Economy', 'Business', 'First Class']) && $nbBillets > 0 && $nbBillets <= 10) {
                $reservation->setClasse($classe);
                $reservation->setNbBillets($nbBillets);
                
                $entityManager->persist($reservation);
                $entityManager->flush();
                
                $this->addFlash('success', 'Your flight has been booked successfully!');
                return $this->redirectToRoute('app_user_flights');
            }
            
            $this->addFlash('error', 'Please check your booking details.');
        }
        
        return $this->render('reservation_vol/book.html.twig', [
            'vol' => $vol,
            'reservation' => $reservation,
        ]);
    }
=======
>>>>>>> f5842df (Initial commit for Events branch)
}
