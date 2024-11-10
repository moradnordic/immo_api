<?php
namespace App\Controller\Admin;

use App\Entity\Document;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Agence;
use App\Entity\User;
use App\Entity\Blogs;
use App\Entity\Property;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(AgenceCrudController::class)->generateUrl());    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Immobilier App');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        // Link to Agence CRUD
        yield MenuItem::linkToCrud('Agences', 'fas fa-building', Agence::class);

        // Link to User CRUD
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);

        // Link to Blogs CRUD
        yield MenuItem::linkToCrud('Blogs', 'fas fa-newspaper', Blogs::class); // Correct icon and label

        // Link to Property CRUD
        yield MenuItem::linkToCrud('Properties', 'fas fa-home', Property::class);

        yield MenuItem::section('Documents');
        yield MenuItem::linkToCrud('Gérer les Documents', 'fa fa-file', Document::class);
    }
}
