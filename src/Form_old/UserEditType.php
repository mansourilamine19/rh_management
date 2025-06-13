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

class UserEditType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('fullName', TextType::class, [
                    'label' => 'Nom complet',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ])
                ->add('email', EmailType::class, [
                    'label' => 'Email',
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => true,
                    )
                ])
                ->add('tel', TelType::class, [
                    'label' => 'Téléphone',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ])
                ->add('roles', ChoiceType::class, array(
                    'label' => 'Roles',
                    'attr' => array(
                        'class' => 'form-control select2',
                        'id' => 'role_selected'
                    ),
                    'choices' =>
                    array_column(RoleUserEnum::cases(), 'name', 'value'),
                    'multiple' => true,
                    'required' => true,
                        )
                )
                ->add('agency', EntityType::class, [
                    'class' => Agency::class,
                    'label' => 'Agence',
                    'required' => false,
                    'choice_label' => 'name',
                    'attr' => array(
                        'class' => 'form-control select2'
                    )
                ])
                ->add('delivery', DeliveryType::class, [
                    'label' => 'Form fournisseur :',
                    'required' => false,
                    //'by_reference' => false,
                    'attr' => array(
                        'class' => 'card-body'
                    )
                ])
                ->add('employee', EmployeeType::class, [
                    'label' => 'Form employée :',
                    'required' => false,
                    //'by_reference' => false,
                    'attr' => array(
                        'class' => 'card-body'
                    )
                ])
                ->add('shipper', ShipperType::class, [
                    'label' => 'Form livreur :',
                    'required' => false,
                    //'by_reference' => false,
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
