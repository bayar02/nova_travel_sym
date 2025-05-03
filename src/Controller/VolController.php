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

    #[Route('/new/ajax', name: 'app_vol_new_ajax', methods: ['POST'])]
    public function newAjax(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Check if it's an AJAX request
        if (!$request->isXmlHttpRequest()) {
            return $this->json(['success' => false, 'message' => 'Only AJAX requests are allowed'], 400);
        }
        
        // Create a new Vol instance
        $vol = new Vol();
        
        // Get data from the request
        $compagnie = $request->request->get('compagnie');
        $destination = $request->request->get('destination');
        $aeroportDepart = $request->request->get('aeroport_depart');
        $aeroportArrivee = $request->request->get('aeroport_arrivee');
        $dateDepart = $request->request->get('date_depart');
        $dateArrivee = $request->request->get('date_arrivee');
        $prix = $request->request->get('prix');
        
        // Validate the data
        $errors = [];
        
        if (empty($compagnie)) {
            $errors['compagnie'] = 'Company name is required';
        }
        
        if (empty($destination)) {
            $errors['destination'] = 'Destination is required';
        }
        
        if (empty($aeroportDepart)) {
            $errors['aeroport_depart'] = 'Departure airport is required';
        }
        
        if (empty($aeroportArrivee)) {
            $errors['aeroport_arrivee'] = 'Arrival airport is required';
        }
        
        if (empty($dateDepart)) {
            $errors['date_depart'] = 'Departure date is required';
        }
        
        if (empty($dateArrivee)) {
            $errors['date_arrivee'] = 'Arrival date is required';
        }
        
        if (empty($prix)) {
            $errors['prix'] = 'Price is required';
        } elseif (!is_numeric($prix) || $prix <= 0) {
            $errors['prix'] = 'Price must be a positive number';
        }
        
        // Check if departure date is before arrival date
        if (!empty($dateDepart) && !empty($dateArrivee)) {
            $departureDate = new \DateTime($dateDepart);
            $arrivalDate = new \DateTime($dateArrivee);
            
            if ($arrivalDate <= $departureDate) {
                $errors['date_arrivee'] = 'Arrival date must be after departure date';
            }
        }
        
        // If there are validation errors, return them
        if (!empty($errors)) {
            return $this->json(['success' => false, 'errors' => $errors], 400);
        }
        
        try {
            // Set the data on the Vol entity
            $vol->setCompagnie($compagnie);
            $vol->setDestination($destination);
            $vol->setAeroportDepart($aeroportDepart);
            $vol->setAeroportArrivee($aeroportArrivee);
            $vol->setDateDepart(new \DateTime($dateDepart));
            $vol->setDateArrivee(new \DateTime($dateArrivee));
            $vol->setPrix((float) $prix);
            
            // Save to database
            $entityManager->persist($vol);
            $entityManager->flush();
            
            // Return success response
            return $this->json(['success' => true, 'message' => 'Flight created successfully']);
            
        } catch (\Exception $e) {
            // Log the error
            error_log($e->getMessage());
            
            // Return error response
            return $this->json(['success' => false, 'message' => 'An error occurred while creating the flight'], 500);
        }
    }

    #[Route('/{id}/edit/ajax', name: 'app_vol_edit_ajax', methods: ['POST'])]
    public function editAjax(Request $request, Vol $vol, EntityManagerInterface $entityManager): Response
    {
        // Check if it's an AJAX request
        if (!$request->isXmlHttpRequest()) {
            return $this->json(['success' => false, 'message' => 'Only AJAX requests are allowed'], 400);
        }
        
        // Get data from the request
        $compagnie = $request->request->get('compagnie');
        $destination = $request->request->get('destination');
        $aeroportDepart = $request->request->get('aeroport_depart');
        $aeroportArrivee = $request->request->get('aeroport_arrivee');
        $dateDepart = $request->request->get('date_depart');
        $dateArrivee = $request->request->get('date_arrivee');
        $prix = $request->request->get('prix');
        
        // Validate the data
        $errors = [];
        
        if (empty($compagnie)) {
            $errors['compagnie'] = 'Company name is required';
        }
        
        if (empty($destination)) {
            $errors['destination'] = 'Destination is required';
        }
        
        if (empty($aeroportDepart)) {
            $errors['aeroport_depart'] = 'Departure airport is required';
        }
        
        if (empty($aeroportArrivee)) {
            $errors['aeroport_arrivee'] = 'Arrival airport is required';
        }
        
        if (empty($dateDepart)) {
            $errors['date_depart'] = 'Departure date is required';
        }
        
        if (empty($dateArrivee)) {
            $errors['date_arrivee'] = 'Arrival date is required';
        }
        
        if (empty($prix)) {
            $errors['prix'] = 'Price is required';
        } elseif (!is_numeric($prix) || $prix <= 0) {
            $errors['prix'] = 'Price must be a positive number';
        }
        
        // Check if departure date is before arrival date
        if (!empty($dateDepart) && !empty($dateArrivee)) {
            $departureDate = new \DateTime($dateDepart);
            $arrivalDate = new \DateTime($dateArrivee);
            
            if ($arrivalDate <= $departureDate) {
                $errors['date_arrivee'] = 'Arrival date must be after departure date';
            }
        }
        
        // If there are validation errors, return them
        if (!empty($errors)) {
            return $this->json(['success' => false, 'errors' => $errors], 400);
        }
        
        try {
            // Update the Vol entity with the new data
            $vol->setCompagnie($compagnie);
            $vol->setDestination($destination);
            $vol->setAeroportDepart($aeroportDepart);
            $vol->setAeroportArrivee($aeroportArrivee);
            $vol->setDateDepart(new \DateTime($dateDepart));
            $vol->setDateArrivee(new \DateTime($dateArrivee));
            $vol->setPrix((float) $prix);
            
            // Save to database
            $entityManager->flush();
            
            // Return success response
            return $this->json(['success' => true, 'message' => 'Flight updated successfully']);
            
        } catch (\Exception $e) {
            // Log the error
            error_log($e->getMessage());
            
            // Return error response
            return $this->json(['success' => false, 'message' => 'An error occurred while updating the flight'], 500);
        }
    }

    #[Route('/search/ajax', name: 'app_vol_search_ajax', methods: ['GET'])]
    public function searchAjax(Request $request, VolRepository $volRepository): Response
    {
        // Get search parameters
        $destination = $request->query->get('destination');
        $aeroportArrivee = $request->query->get('aeroport_arrivee');
        $dateStart = $request->query->get('dateStart');
        $dateEnd = $request->query->get('dateEnd');
        $sortBy = $request->query->get('sortBy', 'date');

        // Build search criteria
        $criteria = array_filter([
            'destination' => $destination,
            'aeroport_arrivee' => $aeroportArrivee,
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
        ]);

        // Get results
        $flights = $volRepository->searchFlights($criteria, $sortBy)
            ->getQuery()
            ->getResult();

        // Format results for JSON response
        $results = array_map(function($flight) {
            return [
                'id' => $flight->getId(),
                'compagnie' => $flight->getCompagnie(),
                'destination' => $flight->getDestination(),
                'aeroportDepart' => $flight->getAeroportDepart(),
                'aeroportArrivee' => $flight->getAeroportArrivee(),
                'dateDepart' => $flight->getDateDepart()->format('Y-m-d H:i'),
                'dateArrivee' => $flight->getDateArrivee()->format('Y-m-d H:i'),
                'prix' => $flight->getPrix(),
            ];
        }, $flights);

        return $this->json(['success' => true, 'flights' => $results]);
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

    #[Route('/admin/new/modal', name: 'admin_vol_new_modal', methods: ['GET'])]
    public function newModal(): Response
    {
        return $this->render('admin/vol/_form_modal.html.twig', [
            'action' => $this->generateUrl('app_vol_new_ajax')
        ]);
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
        
        $flights = $volRepository->findBy([], ['date_depart' => 'DESC']);
        
        return $this->render('admin/vol/_list.html.twig', [
            'flights' => $flights
        ]);
    }
}
