<?php

namespace App\Controller;

use App\Entity\Pet;
use App\Form\PetType;
use App\Repository\PetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pet')]
final class PetController extends AbstractController
{
    #[Route(name: 'app_pet_index', methods: ['GET'])]
    public function index(PetRepository $petRepository): Response
    {
        return $this->render('pet/index.html.twig', [
            'pets' => $petRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pet = new Pet();
        $form = $this->createPetForm($pet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pet);
            $entityManager->flush();

            return $this->redirectToRoute('app_pet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pet/new.html.twig', [
            'pet' => $pet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pet_show', methods: ['GET'])]
    public function show(Pet $pet): Response
    {
        return $this->render('pet/show.html.twig', [
            'pet' => $pet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pet $pet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PetType::class, $pet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pet/edit.html.twig', [
            'pet' => $pet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pet_delete', methods: ['POST'])]
    public function delete(Request $request, Pet $pet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pet->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pet_index', [], Response::HTTP_SEE_OTHER);
    }

    private function createPetForm(Pet $pet = null): FormInterface
    {
        $pet = $pet ?? new Pet();

        return $this->createForm(PetType::class, $pet, [
            'action' => $pet->getId() ? $this->generateUrl('app_pet_edit', ['id' => $pet->getId()]) : $this->generateUrl('app_pet_new'),
        ]);
    }
}
