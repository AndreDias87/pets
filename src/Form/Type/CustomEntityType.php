<?php

namespace App\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

class CustomEntityType extends AbstractType
{
    public function getParent(): string
    {
        return EntityType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'custom_entity';
    }
}
