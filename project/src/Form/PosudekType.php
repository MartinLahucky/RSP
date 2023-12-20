<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Choice;
use App\Form\Type\StarRatingType;

class PosudekType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('aktualnost', StarRatingType::class, [
                'label' => 'Aktualnost',
            ])
            ->add('originalita', StarRatingType::class, [
                'label' => 'Originalita',
            ])
            ->add('odbornaUroven', StarRatingType::class, [
                'label' => 'Odborná úroveň',
            ])
            ->add('jazykovaUroven', StarRatingType::class, [
                'label' => 'Jazyková úroveň',
            ])
            ->add('file', FileType::class,
            [
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '25M',
                        'maxSizeMessage' => 'Maximalni velikost souboru je 25MB',
                    ])
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
