<?php

namespace App\Form;

use App\Entity\Ukol;
use App\Entity\User;
use App\Entity\Clanek;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UkolFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('deadline')
            ->add('description', TextareaType::class,
            [
                'attr' => [
                    'maxlength' => 500,
                ],
                'constraints' => [
                    new Length([
                        'max' => 500,
                        'maxMessage' => 'Maximalni povoleny pocet znaku je {{ limit }}.',
                    ]),
                ],
            ])
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
            ->add('done', CheckboxType::class, [
                'label' => 'Splněno',
                'required' => false
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ukol::class,
        ]);
    }
}
