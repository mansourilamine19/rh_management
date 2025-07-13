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
use App\Enum\TypeContractEnum;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

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
                ->remove('password', PasswordType::class, [
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
                    'data_class' => null,
                    'label' => 'CV',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => false,
                    )
                ])
                ->add('status', ChoiceType::class, array(
                    'label' => 'Status',
                    'attr' => array(
                        'class' => 'form-control select2',
                        'id' => 'status_selected',
                        'required' => true,
                    ),
                    'choices' =>
                    array_column(TypeContractEnum::cases(), 'name', 'value'),
                    'multiple' => false,
                    'required' => false,
                        )
                )
                ->add('manager', EntityType::class, [
                    'class' => User::class,
                    'label' => 'Manager',
                    'query_builder' => function (EntityRepository $er): QueryBuilder {
                        return $er->createQueryBuilder('u')
                                ->where('u.roles LIKE :role')
                                ->setParameter('role', '%ROLE_MANAGER%')
                                ->orderBy('u.fullName', 'ASC');
                    },
                    'required' => false,
                    'placeholder' => '-----',
                    'empty_data' => null,
                    'attr' => array(
                        'class' => 'form-control select2',
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
