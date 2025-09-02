<?php

namespace App\Twig\Components;

use App\Entity\Pet;
use App\Entity\Type;
use App\Repository\TypeRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent('PetTypeRadio')]
final class PetTypeRadio
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public Pet $pet;

    public function __construct(private TypeRepository $typeRepository) {}

    #[ExposeInTemplate]
    public function getOptions(): array
    {
        // Map all types to value/label for RadioGroup
        return array_map(fn(Type $t) => [
            'value' => $t->getId(),
            'label' => $t->getName(),
        ], $this->typeRepository->findAll());
    }

    #[ExposeInTemplate]
    public function getSelected(): ?int
    {
        return $this->pet->getType()?->getId();
    }
}
