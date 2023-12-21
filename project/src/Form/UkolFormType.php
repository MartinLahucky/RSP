<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Ukol;
use App\Entity\User;
use App\Entity\Clanek;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UkolFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('deadline')
            ->add('clanek', EntityType::class,
            [
                'class' => Clanek::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.stav_redakce = :status')
                        ->setParameter('status', \App\Entity\StavRedakce::NOVE_PODANY->value);
                },
                'choice_label' => 'nazev_clanku',
                'label' => 'Článek',
                'required' => true,
            ])
            ->add('user', EntityType::class,
            [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where("c.roles like :status")
                        ->setParameter('status', '%' . \App\Entity\Role::RECENZENT->value . '%');
                },
                'choice_label' => 'email',
                'label' => 'Autor',
                'multiple' => false,
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        /*$resolver->setDefaults([
            'data_class' => Ukol::class,
        ]);*/
    }
}
