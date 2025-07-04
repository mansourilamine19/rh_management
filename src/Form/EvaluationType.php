<?php

namespace App\Form;

use App\Entity\Evaluation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class EvaluationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Collaborateur évalué',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.fullName', 'ASC');
                },
                'required' => true,
                'placeholder' => '-----',
                'empty_data' => null,
                'attr' => [
                    'class' => 'form-control select2',
                    'required' => true,
                ]
            ])
            ->add('evaluateBy', EntityType::class, [
                'class' => User::class,
                'label' => 'Évalué par',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.fullName', 'ASC');
                },
                'required' => true,
                'placeholder' => '-----',
                'empty_data' => null,
                'attr' => [
                    'class' => 'form-control select2',
                    'required' => true,
                ]
            ])
            ->add('note', NumberType::class, [
                'label' => 'Note',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.5',
                    'min' => '0',
                    'max' => '20',
                ]
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evaluation::class,
        ]);
    }
}