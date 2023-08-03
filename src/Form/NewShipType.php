<?php

namespace App\Form;

use App\Entity\Ship;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class NewShipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = [];
        foreach (Ship::$stats as $key => $s) {
            $choices[$s['name'] . " (" . $s['price'][0] . " metal, " . $s['price'][1] . " crystal)"] = $key;
        }
        $builder
            ->add('type', ChoiceType::class, [
                'choices'       => $choices,
            ])
            ->add('build', SubmitType::class, []);
    }
}
