<?php

namespace App\Form;

use App\Entity\ColonyBuilding;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewBuildingType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault("colony_buildings", []);
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = [];
        $colony_buildings_names = [];
        foreach ($options["colony_buildings"] as $b) {
            $colony_buildings_names[] = $b->getName();
        }
        foreach (ColonyBuilding::$stats as $key => $c) {
            if (!in_array($c["name"], $colony_buildings_names)) {
                $choices[$c["name"] . " (" . $c["price"] . " metal)"] = $key;
            }
        }
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => $choices,
                'required' => true,
            ])
            ->add('create', SubmitType::class);
    }
}
