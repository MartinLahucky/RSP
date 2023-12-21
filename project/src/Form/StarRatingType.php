<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StarRatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('star1', CheckboxType::class, [
            //'label' => '★',
            'label' => ' ',
            'required' => false,
        ])
        ->add('star2', CheckboxType::class, [
            //'label' => '★',
            'label' => ' ',
            'required' => false,
        ])
        ->add('star3', CheckboxType::class, [
            //'label' => '★',
            'label' => ' ',
            'required' => false,
        ])
        ->add('star4', CheckboxType::class, [
            //'label' => '★',
            'label' => ' ',
            'required' => false,
        ])
        ->add('star5', CheckboxType::class, [
            //'label' => '★',
            'label' => ' ',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
        ]);
    }
}