<?php

namespace App\Controller;
<<<<<<< HEAD

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
=======
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Builder\BuilderInterface;

use App\Entity\Event;
use App\Form\EventType;
>>>>>>> f5842df (Initial commit for Events branch)
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
<<<<<<< HEAD
=======
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Color\Color;
>>>>>>> f5842df (Initial commit for Events branch)

#[Route('/event')]
final class EventController extends AbstractController
{
    #[Route(name: 'app_event_index', methods: ['GET'])]
<<<<<<< HEAD
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
=======
    public function index(EntityManagerInterface $entityManager): Response
    {
        $events = $entityManager
            ->getRepository(Event::class)
            ->findAll();

        return $this->render('event/index.html.twig', [
            'events' => $events,
>>>>>>> f5842df (Initial commit for Events branch)
        ]);
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }
<<<<<<< HEAD
}
=======

   
        #[Route('/event/{id}/qr', name: 'app_event_qr', methods: ['GET'])]
    public function qr(Event $event): Response
    {
        // 1) Build the text payload
        $text = sprintf(
            "ID: %d\nNom: %s\nDescription: %s\nLieu: %s\nDate: %s\nDurée: %s\nPrix: %s",
            $event->getId(),
            $event->getNom(),
            $event->getDescription(),
            $event->getLieu(),
            $event->getDateEvent() ?: 'N/A',
            $event->getDuree(),
            $event->getPrix()
        );

         
        
        $qrCode = new QrCode($text);
        $qrCode->setSize(300); // Correct method for setting size in v6.x
        
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
       $result->saveToFile($this->getParameter('kernel.project_dir') . '/public/qr-codes/event-' . $event->getId() . '.png'); 
        $filePath = sprintf('qr-codes/event-%d.png', $event->getId());
        $fullPath = $this->getParameter('kernel.project_dir') . '/public/' . $filePath;

        file_put_contents($fullPath, $result->getString());

       
        return $this->redirect($this->generateUrl('app_event_qr_display', ['id' => $event->getId()]));
    }

    #[Route('/event/{id}/qr-display', name: 'app_event_qr_display', methods: ['GET'])]
    public function qrDisplay(Event $event): Response
    {
        $filePath = sprintf('qr-codes/event-%d.png', $event->getId());
        $fullPath = $this->getParameter('kernel.project_dir') . '/public/' . $filePath;

        if (!file_exists($fullPath)) {
            return new Response('QR code not found', Response::HTTP_NOT_FOUND);
        }
        
        return new Response(
            file_get_contents($fullPath),
            Response::HTTP_OK,
            ['Content-Type' => 'image/png']
        );
}

}




>>>>>>> f5842df (Initial commit for Events branch)
