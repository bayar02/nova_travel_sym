<?php

namespace App\Controller;

use App\Entity\ReservationVol;
use App\Form\ReservationVolType;
use App\Repository\ReservationVolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
}
