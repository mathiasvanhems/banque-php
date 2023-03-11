<?php

namespace App\Form;

use App\Entity\Historique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class HistoriqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant')
            ->add('periode', DateType::class, [
                'widget' => 'single_text',
            
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                //'html5' => false,
            
                // adds a class that can be selected in JavaScript
                //'attr' => ['class' => 'js-datepicker'],
                ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Historique::class,
        ]);
    }
}
