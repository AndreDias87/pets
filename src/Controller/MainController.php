<?php

namespace App\Controller;

use App\Entity\Pet;
use App\Entity\SexEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route]
    public function registration(): Response
    {
        $pet = new Pet();
        return $this->render('main/registration.html.twig', [
            'pet' => $pet,
        ]);
    }
}
