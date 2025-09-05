<?php

namespace App\Twig\Components;

use App\Entity\Breed;
use App\Entity\Pet;
use App\Form\PetType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class PetForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?Pet $initialFormData = null;

    protected function instantiateForm(): FormInterface
    {
        $pet = $this->initialFormData ?? new Pet();

        return $this->createForm(PetType::class, $pet, [
            'action' => $pet->getId()
                ? $this->generateUrl('app_pet_edit', ['id' => $pet->getId()])
                : $this->generateUrl('app_pet_new'),
        ]);
    }

    public function helpMessage(): string
    {
        $helpMessage = '';

        $pet = $this->initialFormData ?? new Pet();
        if ($pet->getBreed()?->isDangerous()) {
            $helpMessage = sprintf('⚠️ %s is considered dangerous.', $pet->getBreed()->getName());
        }
        return $helpMessage;
    }
}
