<?php

namespace App\Form;

use App\Entity\StellarObject;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class FleetTravelType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        # ToDo : filter by system
        $builder
            ->add('destination', EntityType::class, [
                'class' => StellarObject::class,
                'choice_label' => 'name',
            ])
            ->add('start', SubmitType::class);
    }
}
