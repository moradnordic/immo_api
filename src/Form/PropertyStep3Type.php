<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class PropertyStep3Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFiles', FileType::class, [
                'multiple' => true,
                'label' => 'Images de la propriété',
                'attr' => [
                    'accept' => 'image/*',
                    'multiple' => 'multiple',
                    'class' => 'form-control',
                ],
                'required' => false,
                'constraints' => [
                    new All([
                        new File([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/jpg',
                                'image/png',
                                'image/gif',
                                'image/webp',
                            ],
                            'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, GIF, WebP).',
                            'maxSizeMessage' => 'L\'image ne doit pas dépasser 5MB.',
                        ])
                    ])
                ],
                'help' => 'Formats acceptés: JPEG, PNG, GIF, WebP. Taille maximale: 5MB par image.',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}