<?php

namespace App\Form;

use App\Entity\Agence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class PropertyStep1Type extends AbstractType
{
    private $marocCity = [
        'Rabat' => 'Rabat',
        'Skhirat' => 'Skhirat',
        'Salé' => 'Salé',
        'Témara' => 'Témara',
        'Kénitra' => 'Kénitra',
        'Mechra Bel Ksiri' => 'Mechra Bel Ksiri',
        'Sidi Kacem' => 'Sidi Kacem',
        'Sidi Yahia' => 'Sidi Yahia',
        'Sidi Slimane' => 'Sidi Slimane',
        'Khemiset' => 'Khemiset',
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $agence = $options['agence'];

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Le titre est obligatoire.']),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                ],
                'required' => false,
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Le prix est obligatoire.']),
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'Le prix ne peut pas être négatif.',
                    ]),
                ],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Appartement' => 'appartement',
                    'Maison' => 'maison',
                    'Ferme' => 'ferme',
                    'Villa' => 'villa',
                    'Bureau' => 'bureau',
                    'Magasin' => 'magasin',
                ],
                'label' => 'Type de propriété',
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Sélectionnez un type',
                'constraints' => [
                    new NotBlank(['message' => 'Le type de propriété est obligatoire.']),
                ],
            ])
            ->add('propertyStatus', ChoiceType::class, [
                'choices' => [
                    'En vente' => 'en_vente',
                    'À louer (vacances)' => 'a_louer_vacances',
                    'À louer (long terme)' => 'a_louer_long_terme',
                ],
                'label' => 'Statut de la propriété',
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Sélectionnez un statut',
                'constraints' => [
                    new NotBlank(['message' => 'Le statut de la propriété est obligatoire.']),
                ],
            ])
            ->add('ageBien', IntegerType::class, [
                'label' => 'Âge du bien',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'L\'âge du bien ne peut pas être négatif.',
                    ]),
                ],
            ])
            ->add('promotion', ChoiceType::class, [
                'choices' => [
                    'Aucune promotion' => 'aucune_promotion',
                    'Promotion spéciale' => 'promotion_speciale',
                    'Offre limitée' => 'offre_limitee',
                ],
                'label' => 'Promotion',
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Sélectionnez une promotion',
                'required' => false,
            ])
            ->add('prixPromo', NumberType::class, [
                'label' => 'Prix Promotionnel',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('type_usage', ChoiceType::class, [
                'choices' => [
                    'Habitation' => 'habitation',
                    'Commerciale' => 'commerciale',
                    'Professionnelle' => 'professionnelle',
                ],
                'label' => 'Type d\'usage',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Le type d\'usage est obligatoire.']),
                ],
            ])
            ->add('surface', NumberType::class, [
                'label' => 'Surface (m²)',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'La surface est obligatoire.']),
                    new GreaterThanOrEqual([
                        'value' => 1,
                        'message' => 'La surface doit être supérieure à 0.',
                    ]),
                ],
            ])
            ->add('city', ChoiceType::class, [
                'choices' => $this->marocCity,
                'label' => 'Ville',
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Sélectionnez une ville',
                'constraints' => [
                    new NotBlank(['message' => 'La ville est obligatoire.']),
                ],
            ])
            ->add('neighborhood', TextType::class, [
                'label' => 'Quartier',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('agence', EntityType::class, [
                'class' => Agence::class,
                'choice_label' => 'name',
                'label' => 'Agence',
                'data' => $agence,
                'disabled' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('sold', CheckboxType::class, [
                'label' => 'Vendu',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'agence' => null,
        ]);
    }
}