<?php

namespace App\Controller;

use App\Repository\CommissionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminDashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(
        CommissionRepository $commissionRepo,
        UserRepository $userRepo
    ): Response
    {
        return $this->render('admin_dashboard/index.html.twig', [
            'totalCommissions' => $commissionRepo->count([]),
            'activeArtists' => 0, // Set to 0 for now
            'totalClients' => $userRepo->count([]),
            'recentCommissions' => $commissionRepo->findBy([], ['createdAt' => 'DESC'], 5),
        ]);
    }
}
