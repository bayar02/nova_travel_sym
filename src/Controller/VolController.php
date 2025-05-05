<?php

namespace App\Controller;

use App\Entity\Vol;
use App\Form\VolType;
use App\Repository\VolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/vol')]
class VolController extends AbstractController
{
    #[Route(name: 'app_vol_index', methods: ['GET'])]
    public function index(VolRepository $volRepository): Response
    {
        return $this->render('vol/index.html.twig', [
            'vols' => $volRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_vol_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // For admin users, redirect to the admin flight index page
        // where they can create flights using the modal
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_flight_index');
        }
        
        // This is for non-admin users or direct access
        $vol = new Vol();
        $form = $this->createForm(VolType::class, $vol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vol);
            $entityManager->flush();

            return $this->redirectToRoute('app_vol_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vol/new.html.twig', [
            'vol' => $vol,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vol_show', methods: ['GET'])]
    public function show(Vol $vol): Response
    {
        return $this->render('vol/show.html.twig', [
            'vol' => $vol,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vol_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vol $vol, EntityManagerInterface $entityManager): Response
    {
        // For admin users, redirect to the admin flight index page
        // where they can edit flights using the modal
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_flight_index');
        }
        
        $form = $this->createForm(VolType::class, $vol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_vol_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vol/edit.html.twig', [
            'vol' => $vol,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vol_delete', methods: ['POST'])]
    public function delete(Request $request, Vol $vol, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vol->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($vol);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vol_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search/ajax', name: 'app_vol_search_ajax', methods: ['GET'])]
    public function searchAjax(Request $request, VolRepository $volRepository): Response
    {
        $destination = $request->query->get('destination');
        $dateStart = $request->query->get('dateStart');
        $dateEnd = $request->query->get('dateEnd');
        $sortBy = $request->query->get('sortBy', 'date');
        
        $criteria = [];
        
        if ($destination) {
            $criteria['destination'] = $destination;
        }
        
        if ($dateStart) {
            $criteria['dateStart'] = $dateStart;
        }
        
        if ($dateEnd) {
            $criteria['dateEnd'] = $dateEnd;
        }
        
        $queryBuilder = $volRepository->searchFlights($criteria, $sortBy);
        $flights = $queryBuilder->getQuery()->getResult();
        
        // Format flights for JSON response
        $formattedFlights = array_map(function($flight) {
            return [
                'id' => $flight->getId(),
                'compagnie' => $flight->getCompagnie(),
                'aeroportDepart' => $flight->getAeroportDepart(),
                'aeroportArrivee' => $flight->getAeroportArrivee(),
                'dateDepart' => $flight->getDateDepart() ? $flight->getDateDepart()->format('d M Y H:i') : '',
                'dateArrivee' => $flight->getDateArrivee() ? $flight->getDateArrivee()->format('d M Y H:i') : '',
                'prix' => $flight->getPrix(),
                'destination' => $flight->getDestination()
            ];
        }, $flights);
        
        return $this->json([
            'success' => true,
            'flights' => $formattedFlights
        ]);
    }

    #[Route('/airports/autocomplete', name: 'app_vol_airports_autocomplete', methods: ['GET'])]
    public function airportsAutocomplete(Request $request, VolRepository $volRepository): Response
    {
        $term = $request->query->get('term', '');
        $type = $request->query->get('type', 'arrival');
        
        $airports = $volRepository->searchAirports($term, $type);
        
        $results = $type === 'destination' 
            ? array_column($airports, 'destination')
            : array_column($airports, 'aeroport_arrivee');
        
        return $this->json(['success' => true, 'airports' => $results]);
    }

    #[Route('/admin/calendar', name: 'admin_calendar', methods: ['GET'])]
    public function calendar(): Response
    {
        return $this->render('admin/calendar.html.twig');
    }

    #[Route('/admin/calendar/events', name: 'admin_calendar_events', methods: ['GET'])]
    public function getCalendarEvents(Request $request, VolRepository $volRepository): Response
    {
        $start = new \DateTime($request->query->get('start'));
        $end = new \DateTime($request->query->get('end'));
        
        $flights = $volRepository->findFlightsBetweenDates($start, $end);
        
        return $this->json($flights);
    }

    #[Route('/admin/vol/new/modal', name: 'admin_vol_new_modal', methods: ['GET'])]
    public function newModal(): Response
    {
        try {
            $vol = new Vol();
            $form = $this->createForm(VolType::class, $vol, [
                'action' => $this->generateUrl('app_vol_new_ajax'),
                'attr' => ['id' => 'flightForm']
            ]);

            return $this->render('admin/vol/_form_modal.html.twig', [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_vol_new_ajax')
            ]);
        } catch (\Exception $e) {
            return new Response('Error creating form: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/admin/{id}/edit/modal', name: 'admin_vol_edit_modal', methods: ['GET'])]
    public function editModal(Vol $vol): Response
    {
        return $this->render('admin/vol/_form_modal.html.twig', [
            'vol' => $vol,
            'action' => $this->generateUrl('app_vol_edit_ajax', ['id' => $vol->getId()])
        ]);
    }

    #[Route('/admin/{id}/delete/confirm', name: 'admin_vol_delete_confirm', methods: ['GET'])]
    public function deleteConfirm(Vol $vol): Response
    {
        return $this->render('admin/vol/_delete_confirm.html.twig', [
            'vol' => $vol
        ]);
    }

    #[Route('/admin/list', name: 'admin_vol_list', methods: ['GET'])]
    public function list(Request $request, VolRepository $volRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        
        $flights = $volRepository->findBy([], ['dateDepart' => 'DESC'], $limit, ($page - 1) * $limit);
        $total = $volRepository->count([]);
        
        return $this->render('admin/vol/_list.html.twig', [
            'flights' => $flights,
            'currentPage' => $page,
            'totalPages' => ceil($total / $limit)
        ]);
    }

    #[Route('/admin/new/ajax', name: 'app_vol_new_ajax', methods: ['POST'])]
    public function newAjax(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vol = new Vol();
        $form = $this->createForm(VolType::class, $vol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vol);
            $entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Flight added successfully'
            ]);
        }

        return $this->json([
            'success' => false,
            'errors' => $this->getFormErrors($form)
        ], Response::HTTP_BAD_REQUEST);
    }

    private function getFormErrors($form): array
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        return $errors;
    }
} 