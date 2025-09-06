<?php

namespace App\Form;

use App\Entity\Breed;
use App\Entity\BreedDetailsEnum;
use App\Entity\Pet;
use App\Entity\SexEnum;
use App\Entity\Type;
use App\Form\Type\CustomChoiceType;
use App\Form\Type\CustomEntityType;
use App\Form\Type\CustomEnumType;
use App\Repository\BreedRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class PetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);

        $builder
            ->add('type', CustomEntityType::class, [
                'class' => Type::class,
                'choice_label' => 'name',
                'label' => 'What kind of pet do you have?',
                'expanded' => true,
                'multiple' => false,
                'priority' => 100,
            ])
            ->add('name', null, [
                'label' => 'What is your pet\'s name?',
                'attr' => [
                    'placeholder' => 'Enter a name',
                ],
                'priority' => 90,
            ])
            ->addDependent('breed', ['type'], function(DependentField $field, ?Type $type) {
                $field->add(EntityType::class, [
                    'class' => Breed::class,
                    'choice_label' => function (?Breed $breed) {
                        return $breed?->getName() !== 'Unknown' ? $breed?->getName() : 'Can’t find it?';
                    },
                    'autocomplete' => false,
                    'label' => 'What breed are they?',
                    'placeholder' => 'Select a breed',
                    'priority' => 80,
                    'query_builder' => function (BreedRepository $repo) use ($type) {
                        $qb = $repo->createQueryBuilder('b')
                            ->andWhere('b.name != :mix')
                            ->setParameter('mix', 'Mix')
                            ->orderBy('b.name', 'ASC');

                        if ($type) {
                            $qb->andWhere('b.type = :type OR b.type IS NULL')
                                ->setParameter('type', $type);
                        }

                        $qb->addSelect("CASE WHEN b.name = 'Unknown' THEN 0 ELSE 1 END AS HIDDEN sort_order")
                            ->orderBy('sort_order', 'ASC')
                            ->addOrderBy('b.name', 'ASC');

                        return $qb;
                    },
                ]);
            })
            ->addDependent('breedDetails', ['breed'], function (DependentField $field, ?Breed $breed) {
                if ($breed?->getName() === 'Unknown') {
                    $field->add(EnumType::class, [
                        'label' => 'Choose one',
                        'class' => BreedDetailsEnum::class,
                        'choice_label' => fn(BreedDetailsEnum $case) => $case->value,
                        'placeholder' => false,
                        'expanded' => true,
                        'multiple' => false,
                        'priority' => 71,
                    ]);
                }
            })
            ->addDependent('customBreedName', ['breedDetails'], function(DependentField $field, ?BreedDetailsEnum $breed) {
                if ($breed === BreedDetailsEnum::MIX) {
                    $field->add(null, [
                        'priority' => 70,
                    ]);
                }
            })
            ->add('sex', CustomEnumType::class, [
                'class' => SexEnum::class,
                'choice_label' => fn(SexEnum $case) => ucfirst($case->value),
                'choice_value' => fn(?SexEnum $case) => $case?->value,
                'expanded' => true,
                'priority' => 60,
                'label' => 'What sex are they?'
            ])
            ->add('birthdayIsKnown', CustomChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'priority' => 50,
                'expanded' => true,
                'label' => 'Do you know their date of birth?',
            ])
            ->addDependent('birthdate', ['birthdayIsKnown'], function(DependentField $field, ?bool $isBirthdayIsKnown) {
                if (!$isBirthdayIsKnown) {
                    return;
                }
                $field->add(BirthdayType::class, [
                    'required' => false,
                    'priority' => 40,
                    'widget' => 'choice',
                    'years' => range(date('Y') - 30, date('Y')),
                ]);
            })
            ->addDependent('approximateAge', ['birthdayIsKnown'], function(DependentField $field, ?bool $isBirthdayIsKnown) {
                if ($isBirthdayIsKnown === null || $isBirthdayIsKnown) {
                    return;
                }

                $field->add(ChoiceType::class, [
                    'mapped' => true,
                    'label' => 'Approximate age (years)',
                    'placeholder' => 'Select age',
                    'priority' => 40,
                    'required' => true,
                    'choices' => array_combine(
                        array_map(fn($n) => $n . ' year' . ($n > 1 ? 's' : ''), range(1, 20)),
                        range(1, 20)
                    ),
                ]);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pet::class,
            'validation_groups' => function (FormInterface $form) {
                $data = $form->getData();
                if ($data->isBirthdayIsKnown()) {
                    return ['Default', 'birthday_known'];
                }
                return ['Default'];
            },
        ]);
    }
}
