<?php

namespace App\Form;

use App\Entity\Mission;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class MissionType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('name', TextType::class, [
                    'label' => 'Nom de la mission',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => true,
                    )
                ])
                ->add('client', TextType::class, [
                    'label' => 'Client',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => true,
                    )
                ])
                ->add('startDate', DateType::class, [
                    'label' => 'date de début',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => true,
                    )
                ])
                ->add('endDate', DateType::class, [
                    'label' => 'Date de fin',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => false,
                    )
                ])
                ->add('tjm', TextType::class, [
                    'label' => 'TJM',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => true,
                    )
                ])
                ->add('user', EntityType::class, [
                    'class' => User::class,
                    'label' => 'Collaborateur',
                    'query_builder' => function (EntityRepository $er): QueryBuilder {
                        return $er->createQueryBuilder('u')
                                ->orderBy('u.fullName', 'ASC');
                    },
                    'required' => false,
                    'placeholder' => '-----',
                    'empty_data' => null,
                    'attr' => array(
                        'class' => 'form-control select2',
                        'required' => true,
                    )
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
