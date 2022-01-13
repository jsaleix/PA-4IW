<?php

namespace App\Form\Back;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            /*->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => false,
                'choices'  => [
                  'User' => 'ROLE_USER',
                  'Seller' => 'ROLE_SELLER',
                  'Admin' => 'ROLE_ADMIN',
                ],
            ])*/
            ->add('avatar')
            ->add('city')
            ->add('address')
            ->add('phone')
            ->add('stripeConnectId')
            ->add('name')
            ->add('surname')
        ;

        $builder->add('roles', ChoiceType::class, array(
            'label' => 'Roles',
            'choices' => User::ROLES,
            //'choice_translation_domain' => 'user',
            'multiple'  => true,
            'expanded' => true,
            'required' => true,
        ));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
