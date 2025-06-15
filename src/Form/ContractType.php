<?php

namespace App\Form;

use App\Entity\Contract;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Enum\TypeContractEnum;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ContractType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
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
                        'required'=> true,
                    )
                ])
                ->add('title', TextType::class, [
                    'label' => 'Titre',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => true,
                    )
                ])
                ->add('type', ChoiceType::class, array(
                    'label' => 'Type',
                    'attr' => array(
                        'class' => 'form-control select2',
                        'id' => 'role_selected',
                        'required' => true,
                    ),
                    'choices' =>
                    array_column(TypeContractEnum::cases(), 'name', 'value'),
                    'multiple' => false,
                    'required' => true,
                        )
                )
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
                ->add('salary', TextType::class, [
                    'label' => 'Salaire',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control',
                        'required' => true,
                    )
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Contract::class,
        ]);
    }
}
