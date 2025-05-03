<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['current_user'];

        $builder
            ->add('name')
            ->add('email')
            ->add('password', PasswordType::class, [
                'mapped' => false, // This prevents Symfony from automatically setting it on the entity
                'required' => true,
                'attr' => ['autocomplete' => 'new-password'], // Improves UX
            ])
            ->add('agence', EntityType::class, [
                'class' => Agence::class,
                'choice_label' => 'name',
                'label' => 'Agence',
            ]);
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            // Admins can choose from all agences
            $builder->add('agence', EntityType::class, [
                'class' => Agence::class,
                'choice_label' => 'name',
                'label' => 'Agence',
            ]);
        } elseif (in_array('ROLE_AGENCE', $user->getRoles())) {
            // Agence users get a fixed agence without choice
            $builder->add('agence', EntityType::class, [
                'class' => Agence::class,
                'choice_label' => 'name',
                'disabled' => true,
                'data' => $user->getAgence(), // Assuming User entity has getAgence()
                'label' => 'Agence',
            ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'current_user' => null,
        ]);
    }
}
