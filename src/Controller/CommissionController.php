<?php

namespace App\Controller;

use App\Entity\Commission;
use App\Entity\User;
use App\Entity\Category;
use App\Form\CommissionType;
use App\Repository\CommissionRepository;
use App\Repository\CategoryRepository;
use App\Service\ActivityLogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/commission')]
final class CommissionController extends AbstractController
{
    public function __construct(
        private ActivityLogService $activityLogService
    ) {}
    #[Route(name: 'app_commission_index', methods: ['GET'])]
    public function index(CommissionRepository $commissionRepository): Response
    {
        return $this->render('commission/index.html.twig', [
            'commissions' => $commissionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        // Only admins and staff can create commissions
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_STAFF')) {
            throw $this->createAccessDeniedException('Access Denied. The user doesn\'t have ROLE_STAFF or ROLE_ADMIN.');
        }

        $commission = new Commission();

        // Assign to current user if they're a staff member
        $user = $this->getUser();
        if ($user instanceof User) {
            $commission->setArtist($user);
        }

        $form = $this->createForm(CommissionType::class, $commission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commission);
            $entityManager->flush();

            // Log activity
            if ($user instanceof User) {
                $this->activityLogService->logCreate(
                    $user,
                    'Commission',
                    $commission->getId(),
                    "Created commission: {$commission->getTitle()}"
                );
            }

            return $this->redirectToRoute('app_commission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commission/new.html.twig', [
            'commission' => $commission,
            'form' => $form,
            'categories' => $categoryRepository->findAll(),
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
    public function edit(Request $request, Commission $commission, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        // Only admins and staff can edit commissions
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_STAFF')) {
            throw $this->createAccessDeniedException('Access Denied. The user doesn\'t have ROLE_STAFF or ROLE_ADMIN.');
        }

        // Staff can only edit their own commissions
        $user = $this->getUser();
        // if ($user instanceof User && !$this->isGranted('ROLE_ADMIN')) {
        //     if ($commission->getArtist() !== $user) {
        //         throw $this->createAccessDeniedException('You can only edit your own commissions.');
        //     }
        // }

        $form = $this->createForm(CommissionType::class, $commission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Log activity
            if ($user instanceof User) {
                $this->activityLogService->logUpdate(
                    $user,
                    'Commission',
                    $commission->getId(),
                    "Updated commission: {$commission->getTitle()}"
                );
            }

            return $this->redirectToRoute('app_commission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commission/edit.html.twig', [
            'commission' => $commission,
            'form' => $form,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_commission_delete', methods: ['POST'])]
    public function delete(Request $request, Commission $commission, EntityManagerInterface $entityManager): Response
    {
        // Only admins can delete commissions (staff can only edit their own)
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$commission->getId(), $request->request->get('_token'))) {
            $commissionId = $commission->getId();
            $commissionTitle = $commission->getTitle();
            
            $entityManager->remove($commission);
            $entityManager->flush();

            // Log activity
            $user = $this->getUser();
            if ($user instanceof User) {
                $this->activityLogService->logDelete(
                    $user,
                    'Commission',
                    $commissionId,
                    "Deleted commission: {$commissionTitle}"
                );
            }
        }

        return $this->redirectToRoute('app_commission_index', [], Response::HTTP_SEE_OTHER);
    }
}
