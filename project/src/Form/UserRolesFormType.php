<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
class UserRolesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('firstname')
            ->add('lastname')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    Role::ADMIN->name => Role::ADMIN->value,
                    Role::SEFREDAKTOR->name => Role::SEFREDAKTOR->value,
                    Role::REDAKTOR->name => Role::REDAKTOR->value,
                    Role::RECENZENT->name => Role::RECENZENT->value,
                    Role::AUTOR->name => Role::AUTOR->value
                    // add more roles here if needed
                ],
                'multiple' => true, // set to true to handle an array of roles
                'expanded' => true, // set to true to render as checkboxes
                'attr' => ['class' => 'dropdown'],
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['autocomplete' => 'new-password'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
