<?php

namespace App\Form;

use App\Entity\Clanek;
use App\Entity\User;
use App\Entity\RecenzniRizeni;
use App\Entity\StavAutor;
use App\Entity\StavRedakce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Choice;

class ClanekFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nazev_clanku')
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
}
