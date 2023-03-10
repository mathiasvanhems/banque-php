<?php

namespace App\Form;

use App\Entity\TypeOperation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TypeOperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('recurrence', ChoiceType::class, [
                'choices'  => [
                    'NA' => 'NA',
                    'Hebdomadaire' => 'Hebdomadaire',
                    'Mensuel' => 'Mensuel',
                ],
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TypeOperation::class,
        ]);
    }
}
