<?php

namespace App\Form;

use App\Entity\Clanek;
use App\Entity\User;
use App\Entity\RecenzniRizeni;
use App\Entity\StavAutor;
use App\Entity\StavRedakce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Choice;

class ClanekFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stav_redakce',ChoiceType::class, [
                'choices' => [
                    StavRedakce::NOVE_PODANY->name => StavRedakce::NOVE_PODANY->value,
                    StavRedakce::CEKA_NA_STANOVENI_RECENZENTU->name => StavRedakce::CEKA_NA_STANOVENI_RECENZENTU->value,
                    StavRedakce::POSUDEK_1_DORUCEN->name => StavRedakce::POSUDEK_1_DORUCEN->value,
                    StavRedakce::POSUDEK_2_DORUCEN->name => StavRedakce::POSUDEK_2_DORUCEN->value,
                    StavRedakce::POSUDKY_ODESLANY_AUTOROVI->name => StavRedakce::POSUDKY_ODESLANY_AUTOROVI->value,
                    StavRedakce::UPRAVA_TEXTU_AUTOREM->name => StavRedakce::UPRAVA_TEXTU_AUTOREM->value,
                    StavRedakce::PRIJATO->name => StavRedakce::PRIJATO->value,
                    StavRedakce::ZAMITNUTO->name => StavRedakce::ZAMITNUTO->value
                ]
            ]
            )
            ->add('stav_autor', ChoiceType::class, [
                'choices' => [
                    StavAutor::PODANO->name => StavAutor::PODANO->value,
                    StavAutor::TEMATICKA_NEVHODNOST->name => StavAutor::TEMATICKA_NEVHODNOST->value,
                    StavAutor::PREDANO_RECENZENTUM->name => StavAutor::PREDANO_RECENZENTUM->value,
                    StavAutor::ZAMITNUTO->name => StavAutor::ZAMITNUTO->value,
                    StavAutor::PRIJATO_S_VYHRADAMI->name => StavAutor::PRIJATO_S_VYHRADAMI->value,
                    StavAutor::OPRAVA_AUTORA->name => StavAutor::OPRAVA_AUTORA->value,
                    StavAutor::DODATECNE_VYJADRENI_AUTORA->name => StavAutor::DODATECNE_VYJADRENI_AUTORA->value,
                    StavAutor::VYJADRENI_SEFREDAKTORA->name => StavAutor::VYJADRENI_SEFREDAKTORA->value,
                    StavAutor::PRIJATO->name => StavAutor::PRIJATO->value,
                ]
            ])
            ->add('nazev_clanku')
            ->add('recenzni_rizeni', EntityType::class,
            [
                'class' => RecenzniRizeni::class,
                'choice_label' => function (RecenzniRizeni $rr): string {
                    return $rr->getOd() . " - " . $rr->getDo();},
                'label' => 'Recenzní řízení'
            ])
            ->add('user',  EntityType::class,  
            [ 
                'class' => User::class,
                'choice_label' => 'email', //Název objektu pro indetifikaci
                'label' => 'Uživatel'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clanek::class,
        ]);
    }
}
