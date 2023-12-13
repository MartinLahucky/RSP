<?php

namespace App\Form;

use App\Entity\Ukol;
use App\Entity\User;
use App\Entity\Clanek;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UkolFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('deadline')
            ->add('clanek', EntityType::class,
            [
                'class' => Clanek::class,
                'choice_label' => 'nazev_clanku',
                'label' => 'Článek'
            ])
            ->add('user', EntityType::class,
            [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getFirstName() . ' ' . $user->getLastName();
                },
                'label' => 'Autor',
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ukol::class,
        ]);
    }
}
