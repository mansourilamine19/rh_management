<?php

namespace App\Form;

use App\Entity\Leave;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class LeaveType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('startDate', DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Date de début',
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                        'required' => true,
                    ]
                ])
                ->add('endDate', DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Date de fin',
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                        'required' => true,
                    ]
                ])
                ->add('type', ChoiceType::class, [
                    'choices' => [
                        'Congé annuel' => 'Congé annuel',
                        'Maladie' => 'Maladie',
                        'Paternité/Maternité' => 'Paternité/Maternité',
                        'Evènement familial' => 'Evènement familial',
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'required' => true,
                    ],
                    'multiple' => false,
                    'expanded' => false, // true = cases à cocher
                ])
                ->remove('status')
                ->remove('user', EntityType::class, [
                    'class' => User::class,
                    'choice_label' => 'id',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Leave::class,
        ]);
    }
}
