<?php

namespace App\Twig\Components;

use App\Entity\Breed;
use App\Entity\Pet;
use App\Entity\SexEnum;
use App\Entity\Type;
use App\Repository\BreedRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\Component\Validator\Constraints as Assert;

#[AsLiveComponent]
final class PetForm
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    public function __construct(
        private TypeRepository $typeRepository,
        private BreedRepository $breedRepository,
    ) {
    }

    // ----------------- Live Props -----------------
    #[LiveProp(writable: true)]
    public Pet $pet;

    #[LiveProp(writable: true)]
    #[Assert\NotBlank]
    public string $name = '';

    #[LiveProp(writable: true)]
    #[Assert\NotNull]
    public ?Type $type = null;

    #[LiveProp(writable: true)]
    public ?Breed $breed = null;

    #[LiveProp(writable: true)]
    public ?string $customBreedName = null;

    #[LiveProp(writable: true)]
    public bool $birthdayIsKnown = true;

    #[LiveProp(writable: true)]
    #[Assert\Range(min: 1900, max: 2100)]
    public ?int $birthYear = null;

    #[LiveProp(writable: true)]
    #[Assert\Range(min: 1, max: 12)]
    public ?int $birthMonth = null;

    #[LiveProp(writable: true)]
    #[Assert\Range(min: 1, max: 31)]
    public ?int $birthDay = null;

    #[LiveProp(writable: true)]
    public ?string $approximateAge = null;

    #[LiveProp(writable: true)]
    public ?SexEnum $sex = null;

    // ----------------- Helper Methods -----------------
    #[ExposeInTemplate]
    public function getTypes(): array
    {
        return $this->typeRepository->findAll();
    }

    #[ExposeInTemplate]
    public function getBreeds(): array
    {
        if (!$this->type) {
            return [];
        }
        return $this->breedRepository->findBy(['type' => $this->type]);
    }

    #[ExposeInTemplate]
    public function getApproximateAgeOptions(): array
    {
        return [
            '0-6 months',
            '6-12 months',
            '1-2 years',
            '2-5 years',
            '5+ years',
        ];
    }

    #[ExposeInTemplate]
    public function getSexes(): array
    {
        return SexEnum::cases();
    }

    // ----------------- Actions -----------------
    #[LiveAction]
    public function save(EntityManagerInterface $em): Response
    {
        $this->validate();

        $pet = new Pet();
        $pet->setName($this->name);
        $pet->setType($this->type);
        $pet->setSex($this->sex);

        if ($this->breed?->getName() === 'Mix') {
            $pet->setBreed($this->breed);
            $pet->setCustomBreedName($this->customBreedName);
        } else {
            $pet->setBreed($this->breed);
            $pet->setCustomBreedName(null);
        }

        if ($this->birthdayIsKnown && $this->birthYear && $this->birthMonth && $this->birthDay) {
            $pet->setBirthdayIsKnown(true);
            $pet->setBirthdate(\DateTime::createFromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $this->birthYear, $this->birthMonth, $this->birthDay)));
        } else {
            $pet->setBirthdayIsKnown(false);
            $pet->setBirthdate(null);
        }

        $pet->setSex($this->sex);

        $em->persist($pet);
        $em->flush();

        $this->name = '';
        $this->type = null;
        $this->breed = null;
        $this->customBreedName = null;
        $this->birthdayIsKnown = true;
        $this->birthYear = $this->birthMonth = $this->birthDay = null;
        $this->approximateAge = null;
        $this->sex = null;

        return new Response('Pet saved!', 200);
    }
}
