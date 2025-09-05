<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class CustomEnumType extends AbstractType
{
    public function getParent(): string
    {
        return EnumType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'custom_enum';
    }
}
