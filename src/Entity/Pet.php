<?php

namespace App\Entity;

use App\Repository\PetRepository;
use DateInterval;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Expression;

#[ORM\Entity(repositoryClass: PetRepository::class)]
class Pet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[NotBlank(groups: ['birthday_known'])]
    private ?DateTime $birthdate = null;

    #[ORM\ManyToOne]
    #[NotBlank]
    private ?Type $type = null;

    #[ORM\ManyToOne]
    #[NotBlank]
    private ?Breed $breed = null;

    #[ORM\Column(nullable: true, enumType: SexEnum::class)]
    #[NotBlank]
    private ?SexEnum $sex = null;

    #[ORM\Column]
    #[NotNull]
    private ?bool $birthdayIsKnown = null;

    #[ORM\Column(length: 255, nullable: true)]
        #[Expression(
        "this.getBreedDetails() !== constant('App\\\\Entity\\\\BreedDetailsEnum::MIX') or (this.getCustomBreedName() !== null)",
        message: "This value should not be blank."
    )]
    private ?string $customBreedName = null;

    #[Expression(
        expression: 'this.isBirthdayIsKnown() or (this.getApproximateAge() !== null)',
        message: 'Approximate age is required if the birthday is not known.'
    )]
    private ?int $approximateAge = null;

    #[Expression(
        expression: "!this.getBreed() or this.getBreed().getName() != 'Unknown' or (this.getBreedDetails() !== null)",
        message: 'Please choose one.'
    )]
    private ?BreedDetailsEnum $breedDetails = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBirthdate(): ?DateTime
    {
        return $this->birthdate;
    }

    public function setBirthdate(?DateTime $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getBreed(): ?Breed
    {
        return $this->breed;
    }

    public function setBreed(?Breed $breed): static
    {
        $this->breed = $breed;

        return $this;
    }

    public function getSex(): ?SexEnum
    {
        return $this->sex;
    }

    public function setSex(?SexEnum $sex): static
    {
        $this->sex = $sex;

        return $this;
    }

    public function isBirthdayIsKnown(): ?bool
    {
        return $this->birthdayIsKnown;
    }

    public function setBirthdayIsKnown(?bool $birthdayIsKnown): static
    {
        $this->birthdayIsKnown = $birthdayIsKnown;

        return $this;
    }

    public function getCustomBreedName(): ?string
    {
        return $this->customBreedName;
    }

    public function setCustomBreedName(?string $customBreedName): static
    {
        $this->customBreedName = $customBreedName;

        return $this;
    }

    public function getApproximateAge(): ?int
    {
        return $this->approximateAge;
    }

    public function setApproximateAge(?int $age): static
    {
        $this->approximateAge = $age;

        // If age is set, calculate birthdate automatically
        if ($age !== null) {
            $this->birthdate = (new DateTime())->sub(new DateInterval('P'.$age.'Y'));;
        }

        return $this;
    }

    public function getBreedDetails(): ?BreedDetailsEnum
    {
        return $this->breedDetails;
    }

    public function setBreedDetails(?BreedDetailsEnum $breedDetails): void
    {
        $this->breedDetails = $breedDetails;
    }

    public function getAge(): ?int
    {
        if ($this->birthdate === null) {
            return null;
        }

        $today = new DateTime('today');
        $diff = $today->diff($this->birthdate);

        return $diff->y;
    }
}
