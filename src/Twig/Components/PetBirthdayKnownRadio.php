<?php

namespace App\Twig\Components;

use App\Entity\Pet;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent('PetBirthdayKnownRadio')]
final class PetBirthdayKnownRadio
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public Pet $pet;

    #[ExposeInTemplate]
    public function getOptions(): array
    {
        return [
            ['value' => true, 'label' => 'Yes'],
            ['value' => false, 'label' => 'No'],
        ];
    }

    #[ExposeInTemplate]
    public function getSelected(): ?bool
    {
        return $this->pet->isBirthdayIsKnown();
    }
}
