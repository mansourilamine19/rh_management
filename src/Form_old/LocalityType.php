<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Locality;
use App\Form\CityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalityType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('name', EntityType::class, [
                    'label' => 'Localité',
                    'class' => Locality::class,
//                    'choices' => [],
                    'required' => true,
                    'empty_data' => '',
                    'attr' => array(
                        'class' => 'form-control',
                    )
                ])
                ->add('city', CityType::class, [
                    'required' => true,
                    'by_reference' => true,
                    'attr' => array(
                        'class' => 'card-body'
                    )
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Locality::class,
        ]);
    }
}
