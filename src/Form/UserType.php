<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('id')
            // ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Storeman' => 'ROLE_STOREMAN',
                    'Admin' => 'ROLE_ADMIN'
                ]
            ])
            ->add('isEnabled', CheckboxType::class)
            ->add('submit', SubmitType::class)
            
        ;
    


        // Data transformer
        $builder->get('roles')
        ->addModelTransformer(new CallbackTransformer(
            function ($rolesArray) {
                 return count($rolesArray) ? $rolesArray[0]: null;
            },
            function ($rolesString) {
                 return [$rolesString];
            }
    ));
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,            
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}