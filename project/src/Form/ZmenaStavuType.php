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
                'TEMATICKA_NEVHODNOST' => 'TEMATICKA NEVHODNOST',
                'PREDANO_RECENZENTUM' => 'PREDANO RECENZENTUM',
                'ZAMITNUTO' => 'ZAMITNUTO',
                'PRIJATO_S_VYHRADAMI' => 'PRIJATO S VYHRADAMI',
                'OPRAVA_AUTORA' => 'OPRAVA AUTORA',
                'DODATECNE_VYJADRENI_AUTORA' => 'DODATECNE VYJADRENI AUTORA',
                'VYJADRENI_SEFREDAKTORA' => 'CEKANI NA VYJADRENI SEFREDAKTORA',
                'PRIJATO' => 'PRIJATO',
                ],
        ])
        ->add('stavRedakce', ChoiceType::class, [
            'label' => 'Stav redakce',
            'choices'  => [
                'NOVE_PODANY' => 'NOVE PODANY',
                'CEKA_NA_STANOVENI_RECENZENTU' => 'CEKA NA STANOVENI RECENZENTU',
                'POSUDEK_1_DORUCEN' => 'POSUDEK 1 DORUCEN',
                'POSUDEK_2_DORUCEN' => 'POSUDEK 2 DORUCEN',
                'POSUDKY_ODESLANY_AUTOROVI' => 'POSUDKY ODESLANY AUTOROVI',
                'UPRAVA_TEXTU_AUTOREM' => 'UPRAVA TEXTU AUTOREM',
                'VYJADRENI_SEFREDAKTORA' => 'CEKANI NA VYJADRENI SEFREDAKTORA',
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
