<?php

namespace App\Form;

use App\Entity\Region;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegionType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('name', EntityType::class, [
                    'class' => Region::class,
                    'label' => 'Gouvernerat',
                    'query_builder' => function (EntityRepository $er): QueryBuilder {
                        return $er->createQueryBuilder('r');
                    },
                    'required' => true,
                    'placeholder' => '-----',
                    'empty_data' => null,
                    'attr' => array(
                        'class' => 'form-control select2'
                    )
                ])
                ->remove('code')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Region::class,
        ]);
    }
}
