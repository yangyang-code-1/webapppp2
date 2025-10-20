<?php

namespace App\Controller;

use App\Entity\Commission;
use App\Entity\User; 
use App\Form\CommissionType;
use App\Repository\CommissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/commission')]
final class CommissionController extends AbstractController
{
    #[Route(name: 'app_commission_index', methods: ['GET'])]
    public function index(CommissionRepository $commissionRepository): Response
    {
        return $this->render('commission/index.html.twig', [
            'commissions' => $commissionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // $commission = new Commission();
        // $form = $this->createForm(CommissionType::class, $commission);
        // $form->handleRequest($request);
        $commission = new Commission();

        // Temporary: assign to first user (artist) in DB
        $artist = $entityManager->getRepository(User::class)->find(1);
        $commission->setArtist($artist);

        // Once login works, you’ll replace it with:
        // $commission->setArtist($this->getUser());

        // Continue as usual
        $form = $this->createForm(CommissionType::class, $commission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commission);
            $entityManager->flush();

            return $this->redirectToRoute('app_commission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commission/new.html.twig', [
            'commission' => $commission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commission_show', methods: ['GET'])]
    public function show(Commission $commission): Response
    {
        return $this->render('commission/show.html.twig', [
            'commission' => $commission,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commission $commission, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommissionType::class, $commission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commission/edit.html.twig', [
            'commission' => $commission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commission_delete', methods: ['POST'])]
    public function delete(Request $request, Commission $commission, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commission->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commission_index', [], Response::HTTP_SEE_OTHER);
    }
}
