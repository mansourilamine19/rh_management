<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Region;
use App\Form\RegionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('name', EntityType::class, [
                    'label' => 'Ville',
                    'class' => City::class,
                    //'choices' => [],
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => true,
                    )
                ])
                ->add('region', EntityType::class, [
                    'label' => 'Région',
                    'class' => Region::class,
                    'required' => false,
                    //'by_reference' => true,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => true,
                    )
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => City::class,
        ]);
    }
}
