<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LegalController extends AbstractController
{
    #[Route('/mentions-légales', name: 'app_mentions_legales')]
    public function mentionLegales(): Response
    {
        return $this->render('legal/mentions_legales.html.twig', [
            'controller_name' => 'LegalController',
        ]);
    }

    #[Route('/politique-de-confidentialité', name: 'app_politique_confidentialite')]
    public function politiqueConfidentialite(): Response
    {
        return $this->render('legal/politique_confidentialite.html.twig', [
            'controller_name' => 'LegalController',
        ]);
    }
}
