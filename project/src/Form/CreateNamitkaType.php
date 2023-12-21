<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Length;

class CreateNamitkaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text_namitky', TextareaType::class,
            [
                'required' => true,
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'maxlength' => 10000,
                ],
                'constraints' => [
                    new Length([
                        'max' => 10000,
                        'maxMessage' => 'Maximalni povoleny pocet znaku je {{ limit }}.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
