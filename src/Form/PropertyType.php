<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\Property;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
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

    private $etage = [
        '2ème sous sol' => -5,
        '1er sous sol' => -4,
        'Entresol' => -3,
        'Rez de jardin' => -2,
        'Rez de chaussée' => -1,
        '1er étage' => 1,
        '2ème étage' => 2,
        '3ème étage' => 3,
        '4ème étage' => 4,
        '5ème étage' => 5,
        '6ème étage' => 6,
        '7ème étage' => 7,
        '8ème étage' => 8,
        '9ème étage' => 9,
        '10ème étage' => 10,
        '11ème étage' => 11,
        '12ème étage' => 12,
        '13ème étage' => 13,
        '14ème étage' => 14,
        '15ème étage' => 15,
        '16ème étage' => 16,
        '17ème étage' => 17,
        '18ème étage' => 18,
        '19ème étage' => 19,
        '20ème étage' => 20,
        '21ème étage' => 21,
        '22ème étage' => 22,
        '23ème étage' => 23,
        '24ème étage' => 24,
        '25ème étage' => 25,
        '26ème étage' => 26,
        '27ème étage' => 27,
        '28ème étage' => 28,
        '29ème étage' => 29,
        '30ème étage' => 30,
        '31ème étage' => 31,
        '32ème étage' => 32,
        '33ème étage' => 33,
        '34ème étage' => 34,
        '35ème étage' => 35,
        '36ème étage' => 36,
        '37ème étage' => 37,
        '38ème étage' => 38,
        '39ème étage' => 39,
        '40ème étage' => 40,
        '41ème étage' => 41,
        '42ème étage' => 42,
        '43ème étage' => 43,
        '44ème étage' => 44,
        '45ème étage' => 45,
        '46ème étage' => 46,
        '47ème étage' => 47,
        '48ème étage' => 48,
        '49ème étage' => 49,
        '50ème étage' => 50,
    ];
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user']; // Get the user passed from the controller
        $agence = $options['agence']; // Get the agence passed from the controller
$builder
    ->add('title', null, [
        'label' => 'Titre',
    ])
    ->add('description', null, [
        'label' => 'Description',
    ])
    ->add('nbrEtage', ChoiceType::class, [
        'label' => 'Numéro d\'étage',
        'placeholder' => 'Numéro d\'étage',
        'choices' =>$this->etage,
        'required' => false, // Make it optional if needed
        ])
    ->add('price', null, [
        'label' => 'Prix',
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
    ])
    ->add('surface', null, [
        'label' => 'Surface',
    ])
    ->add('rooms', null, [
        'label' => 'Chambres',
    ])
    ->add('beds', null, [
        'label' => 'Lits',
    ])
    ->add('type', ChoiceType::class, [
        'choices' => [
            'Appartement' => 'appartement',
            'Maison' => 'maison',
            'Ferme' => 'ferme',
            'Villa' => 'villa',
        ],
        'label' => 'Type de propriété',
        'attr' => ['class' => 'form-control'],
        'placeholder' => 'Sélectionnez un type',
    ])
    ->add('type_usage', ChoiceType::class, [
        'choices' => [
            'Habitation' => 'habitation',
            'Commerciale' => 'commerciale',
            'Professionnelle' => 'professionnelle',
        ],
        'label' => 'Type d\'usage'
    ])
    ->add('meubler')
    ->add('propertyStatus', ChoiceType::class, [
        'choices' => [
            'En vente' => 'en_vente',
            'À louer (vacances)' => 'a_louer_vacances',
            'À louer (long terme)' => 'a_louer_long_terme',
        ],
        'label' => 'Statut de la propriété',
        'attr' => ['class' => 'form-control'],
        'placeholder' => 'Sélectionnez un statut',
    ])
    ->add('prixPromo', NumberType::class, [
        'label' => 'Prix Promotionnel',
        'attr' => ['class' => 'form-control'],
        'required' => false,
    ])
    ->add('bath', null, [
        'label' => 'Salles de bain',
    ])
    ->add('sold', CheckboxType::class, [
        'label' => 'Vendu',
        'required' => false,
    ])
    ->add('imageFiles', FileType::class, [
        'multiple' => true,
        'label' => 'Fichiers d\'image',
        'attr' => [
            'accept' => 'image/*',
            'multiple' => 'multiple',
        ],
    ])

    ->add('city', ChoiceType::class, [
        'choices' => $this->marocCity,
        'label' => 'Ville',
        'attr' => ['class' => 'form-control'],
        'placeholder' => 'Sélectionnez une ville',
    ])
    ->add('neighborhood', TextType::class, [
        'label' => 'Quartier',
        'attr' => ['class' => 'form-control'],
    ])
    ->add('agence', EntityType::class, [
        'class' => Agence::class,
        'choice_label' => 'name',
        'label' => 'Agence',
        'data' => $agence,
        'disabled' => true,
    ])
;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'user' => null, // Default value for the user
            'agence' => null, // Default value for the agence
        ]);
    }
}
