<?php

namespace App\Form;

use App\Entity\Agency;
use App\Entity\User;
use App\Form\ShipperType;
use App\Form\DeliveryType;
use App\Form\EmployeeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Enum\RoleUserEnum;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('fullName', TextType::class, [
                    'label' => 'Nom complet',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => true,
                    )
                ])
                ->add('email', EmailType::class, [
                    'label' => 'Email',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => true,
                    )
                ])
                ->add('tel', TelType::class, [
                    'label' => 'Téléphone',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => false,
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
                ->add('agency', EntityType::class, [
                    'class' => Agency::class,
                    'label' => 'Agence',
                    'required' => true,
                    'choice_label' => 'name',
                    'attr' => array(
                        'class' => 'form-control select2',
                        'required' => true,
                    )
                ])
                ->add('delivery', DeliveryType::class, [
                    'label' => 'Form livreur :',
                    'required' => false,
                    'by_reference' => false,
                    'attr' => array(
                        'class' => 'card-body'
                    )
                ])
                ->add('employee', EmployeeType::class, [
                    'label' => 'Form employée :',
                    'required' => false,
                    'by_reference' => false,
                    'attr' => array(
                        'class' => 'card-body'
                    )
                ])
                ->add('shipper', ShipperType::class, [
                    'label' => 'Form fournisseur :',
                    'required' => false,
                    'by_reference' => false,
                    'attr' => array(
                        'class' => 'card-body'
                    )
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => User::class,
//            'cascade_validation' => true,
//            'constraints' => new \Symfony\Component\Validator\Constraints\Valid(),
        ]);
    }
}
