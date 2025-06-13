<?php

namespace App\Form;

use App\Entity\Contract;
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
use App\Enum\RoleUserEnum;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class User1Type extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('email', EmailType::class, [
                    'label' => 'Email',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => true,
                    )
                ])
                ->add('roles', ChoiceType::class, array(
                    'label' => 'Roles',
                    'attr' => array(
                        'class' => 'form-control select2',
                        'id' => 'role_selected',
                        'required' => true,
                    ),
                    'choices' =>
                    array_column(RoleUserEnum::cases(), 'name', 'value'),
                    'multiple' => true,
                    'required' => true,
                        )
                )
                ->add('password', PasswordType::class, [
                    'label' => 'Mot de passe',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => true,
                    )
                ])
                ->remove('isVerified')
                ->add('tel', TelType::class, [
                    'label' => 'Téléphone',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => false,
                    )
                ])
                ->add('fullName', TextType::class, [
                    'label' => 'Nom complet',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => true,
                    )
                ])
                ->add('adresse', TextType::class, [
                    'label' => 'Adresse',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => true,
                    )
                ])
                ->add('cv', FileType::class, [
                    'label' => 'CV',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => true,
                    )
                ])
                ->remove('status')
                ->add('manager', EntityType::class, [
                    'class' => User::class,
                    'choice_label' => 'fullName',
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => false,
                    )
                ])
                ->remove('contract', EntityType::class, [
                    'class' => Contract::class,
                    'choice_label' => 'id',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
