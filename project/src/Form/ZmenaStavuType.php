<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ZmenaStavuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('stavAutor', ChoiceType::class, [
            'label' => 'Stav autora',
            'choices'  => [
                'PODANO' => 'PODANO',
                'TEMATICKA NEVHODNOST' => 'TEMATICKA NEVHODNOST',
                'PREDANO RECENZENTUM' => 'PREDANO RECENZENTUM',
                'ZAMITNUTO' => 'ZAMITNUTO',
                'PRIJATO S VYHRADAMI' => 'PRIJATO S VYHRADAMI',
                'OPRAVA AUTORA' => 'OPRAVA AUTORA',
                'DODATECNE VYJADRENI AUTORA' => 'DODATECNE VYJADRENI AUTORA',
                'VYJADRENI SEFREDAKTORA' => 'CEKANI NA VYJADRENI SEFREDAKTORA',
                'PRIJATO' => 'PRIJATO',
                ],
        ])
        ->add('stavRedakce', ChoiceType::class, [
            'label' => 'Stav redakce',
            'choices'  => [
                'NOVE PODANY' => 'NOVE PODANY',
                'CEKA NA STANOVENI RECENZENTU' => 'CEKA NA STANOVENI RECENZENTU',
                'POSUDEK 1 DORUCEN' => 'POSUDEK 1 DORUCEN',
                'POSUDEK 2 DORUCEN' => 'POSUDEK 2 DORUCEN',
                'POSUDKY ODESLANY AUTOROVI' => 'POSUDKY ODESLANY AUTOROVI',
                'UPRAVA TEXTU AUTOREM' => 'UPRAVA TEXTU AUTOREM',
                'VYJADRENI SEFREDAKTORA' => 'CEKANI NA VYJADRENI SEFREDAKTORA',
                'PRIJATO' => 'PRIJATO',
                'ZAMITNUTO' => 'ZAMITNUTO',
                ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    
    }
}
