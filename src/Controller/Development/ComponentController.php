<?php

namespace App\Controller\Development;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComponentController extends AbstractController
{
    #[Route('/dev/components', name: 'app_development_component')]
    public function index(): Response
    {
        return $this->render('dev/components/index.html.twig', [
            'controller_name' => 'DevelopmentComponentController',
        ]);
    }
}
