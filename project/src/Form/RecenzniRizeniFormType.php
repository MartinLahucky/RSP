<?php

namespace App\Form;

use App\Entity\RecenzniRizeni;
use App\Entity\Tisk;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecenzniRizeniFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('od')
            ->add('do')
            ->add('tisk', EntityType::class,
            [
                'class' => Tisk::class,
                'choice_label' => 'datum',
                'label' => 'Tisk'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecenzniRizeni::class,
        ]);
    }
}
