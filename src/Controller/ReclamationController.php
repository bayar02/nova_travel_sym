<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Snappy\Pdf;
use Doctrine\ORM\Mapping as ORM;
use Knp\Snappy\Pdf as SnappyPdf;
use Knp\Snappy\Pdf\Response as PdfResponse;
use Dompdf\Dompdf;
#[Route('/reclamation')]
final class ReclamationController extends AbstractController
{




    #[Route(name: 'app_reclamation_index', methods: ['GET'])]
    public function index(Request $request, ReclamationRepository $reclamationRepository, PaginatorInterface $paginator): Response
{
    $query = $reclamationRepository->createQueryBuilder('r')->getQuery();


    $reclamations = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1), 
        3 // number of items per page
    );

    return $this->render('reclamation/index.html.twig', [
        'reclamations' => $reclamations,
    ]);
}

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
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

            return $this->redirectToRoute('app_reclamation_index');
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
  ////////////////////////////////////////////// FRONT //////////////////////////////////////
  #[Route('/reclamation/{id}/pdf', name: 'reclamation_pdf')]
  public function reclamationPdf(int $id, EntityManagerInterface $entityManager): Response
  {
      // Récupérer le régime concerné
      $reclamation = $entityManager->getRepository(Reclamation::class)->find($id);
  
     
      // Générer le HTML pour le régime
      $html = $this->renderView('reclamation/reclamation_pdf.html.twig', [
          'reclamation' => $reclamation,
      ]);
  
      // Configurer Dompdf
      $dompdf = new Dompdf();
      $dompdf->loadHtml($html);
      $dompdf->setPaper('A4', 'portrait');
      $dompdf->render();
  
      // Retourner le PDF en réponse
      return new Response($dompdf->output(), 200, [
          'Content-Type' => 'application/pdf',
          'Content-Disposition' => 'inline; filename="reclamation_' . $reclamation->getId() . '.pdf"',
      ]);
  }




}
