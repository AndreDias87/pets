<?php

namespace App\Twig\Components;

use App\Entity\Pet;
use App\Entity\SexEnum;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
final class PetSexRadio
{
    use DefaultActionTrait;

    public Pet $pet;

    #[ExposeInTemplate]
    public function getOptions(): array
    {
        return array_map(fn(SexEnum $sex) => [
            'value' => $sex->value,
            'label' => ucfirst(strtolower($sex->name)),
        ], SexEnum::cases());
    }

    #[ExposeInTemplate]
    public function getSelected(): ?string
    {
        return $this->pet->getSex()?->value;
    }
}
