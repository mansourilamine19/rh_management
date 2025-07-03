<?php

namespace App\Form;

use App\Entity\Skill;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Compétence',
                'attr' => ['class' => 'form-control']
            ])
            ->add('level', NumberType::class, [
                'label' => 'Niveau',
                'attr' => ['class' => 'form-control']
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'fullName',
                'label' => 'Collaborateur',
                'attr' => ['class' => 'form-control select2']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Skill::class,
        ]);
    }
}
