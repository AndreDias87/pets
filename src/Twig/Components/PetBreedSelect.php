<?php

namespace App\Twig\Components;

use App\Entity\Breed;
use App\Entity\Pet;
use App\Entity\Type;
use App\Repository\BreedRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent('PetBreedSelect')]
final class PetBreedSelect
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public Pet $pet;

    #[LiveProp(writable: true)]
    public ?Type $type = null;

    #[LiveProp(writable: true)]
    public ?Breed $breed = null;

    #[LiveProp(writable: true)]
    public ?string $customBreedName = null;

    public function __construct(private BreedRepository $breedRepository) {}

    #[ExposeInTemplate]
    public function getBreeds(): array
    {
        $query = $this->type ? ['type' => $this->type] : [];
        $breeds = $this->breedRepository->findBy($query);

        // Filter out Mix and Unknown
        return array_filter($breeds, fn(Breed $b) => !in_array($b->getName(), ['Mix', 'Unknown']));
    }

    #[ExposeInTemplate]
    public function showCustomInput(): bool
    {
        return $this->breed && in_array($this->breed->getName(), ['Mix', 'Unknown']);
    }
}
