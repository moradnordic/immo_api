<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\Property;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

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

    private $orientationChoices = [
        'Nord' => 'nord',
        'Sud' => 'sud',
        'Est' => 'est',
        'Ouest' => 'ouest',
        'Nord-Est' => 'nord-est',
        'Nord-Ouest' => 'nord-ouest',
        'Sud-Est' => 'sud-est',
        'Sud-Ouest' => 'sud-ouest',
    ];

    private $etatChoices = [
        'Neuf' => 'neuf',
        'Bon état' => 'bon_etat',
        'À rénover' => 'a_renover',
        'En construction' => 'en_construction',
    ];

    private $facadeChoices = [
        'Cour' => 'cour',
        'Rue' => 'rue',
        'Jardin' => 'jardin',
        'Vue mer' => 'vue_mer',
        'Vue montagne' => 'vue_montagne',
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user']; // Get the user passed from the controller
        $agence = $options['agence']; // Get the agence passed from the controller

        // Common fields (always visible)
        $builder
            ->add('title', null, [
                'label' => 'Titre',
                'attr' => ['class' => 'common-field'],
            ])
            ->add('description', null, [
                'label' => 'Description',
                'attr' => ['class' => 'common-field'],
            ])
            ->add('price', null, [
                'label' => 'Prix',
                'attr' => ['class' => 'common-field'],
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
                'attr' => [
                    'class' => 'form-control property-type-select common-field',
                    'data-property-type-selector' => 'true',
                ],
                'placeholder' => 'Sélectionnez un type',
            ])
            ->add('propertyStatus', ChoiceType::class, [
                'choices' => [
                    'En vente' => 'en_vente',
                    'À louer (vacances)' => 'a_louer_vacances',
                    'À louer (long terme)' => 'a_louer_long_terme',
                ],
                'label' => 'Statut de la propriété',
                'attr' => ['class' => 'form-control common-field'],
                'placeholder' => 'Sélectionnez un statut',
            ])
            ->add('ageBien', IntegerType::class, [
                'label' => 'Âge du bien',
                'required' => false,
                'attr' => ['class' => 'common-field'],
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
                'attr' => ['class' => 'form-control common-field'],
                'placeholder' => 'Sélectionnez une promotion',
            ])
            ->add('prixPromo', NumberType::class, [
                'label' => 'Prix Promotionnel',
                'attr' => ['class' => 'form-control common-field'],
                'required' => false,
            ])
            ->add('type_usage', ChoiceType::class, [
                'choices' => [
                    'Habitation' => 'habitation',
                    'Commerciale' => 'commerciale',
                    'Professionnelle' => 'professionnelle',
                ],
                'label' => 'Type d\'usage',
                'attr' => ['class' => 'common-field'],
            ])
            ->add('surface', null, [
                'label' => 'Surface',
                'attr' => ['class' => 'common-field'],
            ])
            ->add('city', ChoiceType::class, [
                'choices' => $this->marocCity,
                'label' => 'Ville',
                'attr' => ['class' => 'form-control common-field'],
                'placeholder' => 'Sélectionnez une ville',
            ])
            ->add('neighborhood', TextType::class, [
                'label' => 'Quartier',
                'attr' => ['class' => 'form-control common-field'],
            ])
            ->add('agence', EntityType::class, [
                'class' => Agence::class,
                'choice_label' => 'name',
                'label' => 'Agence',
                'data' => $agence,
                'disabled' => true,
                'attr' => ['class' => 'common-field'],
            ])
            ->add('sold', CheckboxType::class, [
                'label' => 'Vendu',
                'required' => false,
                'attr' => ['class' => 'common-field'],
            ])
            ->add('imageFiles', FileType::class, [
                'multiple' => true,
                'label' => 'Fichiers d\'image',
                'attr' => [
                    'accept' => 'image/*',
                    'multiple' => 'multiple',
                    'class' => 'common-field',
                ],
                'required' => false,
            ]);

        // Appartement specific fields
        $builder
            ->add('rooms', null, [
                'label' => 'Chambres',
                'attr' => ['class' => 'appartement-field'],
                'required' => false,
            ])
            ->add('beds', null, [
                'label' => 'Lits',
                'attr' => ['class' => 'appartement-field'],
                'required' => false,
            ])
            ->add('bath', null, [
                'label' => 'Salles de bain',
                'attr' => ['class' => 'appartement-field'],
                'required' => false,
            ])
            ->add('nbrEtage', ChoiceType::class, [
                'label' => 'Numéro d\'étage',
                'placeholder' => 'Numéro d\'étage',
                'choices' => $this->etage,
                'required' => false,
                'attr' => ['class' => 'appartement-field'],
            ])
            ->add('meubler', CheckboxType::class, [
                'label' => 'Meublé',
                'required' => false,
                'attr' => ['class' => 'appartement-field'],
            ])
            ->add('concierge', CheckboxType::class, [
                'label' => 'Concierge',
                'required' => false,
                'attr' => ['class' => 'appartement-field'],
            ])
            ->add('residenceSurveillee', CheckboxType::class, [
                'label' => 'Résidence surveillée',
                'required' => false,
                'attr' => ['class' => 'appartement-field'],
            ])
            ->add('charges', IntegerType::class, [
                'label' => 'Charges',
                'required' => false,
                'attr' => ['class' => 'appartement-field'],
            ])
            ->add('etat', ChoiceType::class, [
                'label' => 'État',
                'choices' => $this->etatChoices,
                'required' => false,
                'attr' => ['class' => 'appartement-field'],
                'placeholder' => 'Sélectionnez un état',
            ])
            ->add('orientation', ChoiceType::class, [
                'label' => 'Orientation',
                'choices' => $this->orientationChoices,
                'required' => false,
                'attr' => ['class' => 'appartement-field'],
                'placeholder' => 'Sélectionnez une orientation',
            ])
            ->add('chauffageIndividuel', CheckboxType::class, [
                'label' => 'Chauffage individuel',
                'required' => false,
                'attr' => ['class' => 'appartement-field'],
            ])
            ->add('hauteur', IntegerType::class, [
                'label' => 'Hauteur (cm)',
                'required' => false,
                'attr' => ['class' => 'appartement-field'],
            ])
            ->add('ascenseur', CheckboxType::class, [
                'label' => 'Ascenseur',
                'required' => false,
                'attr' => ['class' => 'appartement-field'],
            ])
            ->add('facadePrincipale', ChoiceType::class, [
                'label' => 'Façade principale',
                'choices' => $this->facadeChoices,
                'required' => false,
                'attr' => ['class' => 'appartement-field'],
                'placeholder' => 'Sélectionnez une façade',
            ])
            ->add('climatisation', CheckboxType::class, [
                'label' => 'Climatisation',
                'required' => false,
                'attr' => ['class' => 'appartement-field'],
            ])
            ->add('dpeKwh', IntegerType::class, [
                'label' => 'Diagnostic de performance énergétique (kWh/m²/an)',
                'required' => false,
                'attr' => ['class' => 'appartement-field'],
            ]);

        // Maison/Villa specific fields
        $builder
            ->add('nombreNiveaux', IntegerType::class, [
                'label' => 'Nombre de niveaux',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('surfaceTerrain', IntegerType::class, [
                'label' => 'Surface terrain (m²)',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('surfaceConstruite', IntegerType::class, [
                'label' => 'Surface construite (m²)',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('terrasse', CheckboxType::class, [
                'label' => 'Terrasse',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('balcon', CheckboxType::class, [
                'label' => 'Balcon',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('parkingAbrite', CheckboxType::class, [
                'label' => 'Place de parking abritée',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('parkingExterieur', CheckboxType::class, [
                'label' => 'Place de parking extérieure',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('climatisation', CheckboxType::class, [
                'label' => 'Climatisation',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('piscine', CheckboxType::class, [
                'label' => 'Piscine',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('jardin', CheckboxType::class, [
                'label' => 'Jardin',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('energiePhotovoltaique', CheckboxType::class, [
                'label' => 'Énergie photovoltaïque',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('orientation', ChoiceType::class, [
                'label' => 'Orientation',
                'choices' => $this->orientationChoices,
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
                'placeholder' => 'Sélectionnez une orientation',
            ])
            ->add('chauffageIndividuel', CheckboxType::class, [
                'label' => 'Chauffage individuel',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('logeDomestique', CheckboxType::class, [
                'label' => 'Loge / Pièce domestique',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('debarras', CheckboxType::class, [
                'label' => 'Débarras',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('dpeKwh', IntegerType::class, [
                'label' => 'Diagnostic de performance énergétique (kWh/m²/an)',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('dpeCo2', IntegerType::class, [
                'label' => 'Diagnostic de performance énergétique (CO₂/m²/an)',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('dateConstruction', DateType::class, [
                'label' => 'Date de construction',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('dateRenovation', DateType::class, [
                'label' => 'Date de dernière rénovation',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('camera', CheckboxType::class, [
                'label' => 'Caméra',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('alarme', CheckboxType::class, [
                'label' => 'Alarme',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ])
            ->add('residenceFermee', CheckboxType::class, [
                'label' => 'Résidence fermée',
                'required' => false,
                'attr' => ['class' => 'maison-villa-field'],
            ]);

        // Bureau specific fields
        $builder
            ->add('surfaceConstruite', IntegerType::class, [
                'label' => 'Surface Construite (m²)',
                'required' => false,
                'attr' => ['class' => 'bureau-field'],
            ])
            ->add('surfaceHabitable', IntegerType::class, [
                'label' => 'Surface Habitable (m²)',
                'required' => false,
                'attr' => ['class' => 'bureau-field'],
            ])
            ->add('openSpace', CheckboxType::class, [
                'label' => 'Open Space',
                'required' => false,
                'attr' => ['class' => 'bureau-field'],
            ])
            ->add('cloisonDur', CheckboxType::class, [
                'label' => 'Cloison en Dur',
                'required' => false,
                'attr' => ['class' => 'bureau-field'],
            ])
            ->add('parking', CheckboxType::class, [
                'label' => 'Place Parking',
                'required' => false,
                'attr' => ['class' => 'bureau-field'],
            ])
            ->add('cuisine', CheckboxType::class, [
                'label' => 'Cuisine',
                'required' => false,
                'attr' => ['class' => 'bureau-field'],
            ])
            ->add('wc', CheckboxType::class, [
                'label' => 'WC',
                'required' => false,
                'attr' => ['class' => 'bureau-field'],
            ])
            ->add('nbrEtage', ChoiceType::class, [
                'label' => 'Étage',
                'placeholder' => 'Sélectionnez un étage',
                'choices' => $this->etage,
                'required' => false,
                'attr' => ['class' => 'bureau-field'],
            ])
            ->add('immeubleBureau', CheckboxType::class, [
                'label' => 'Immeuble Bureau',
                'required' => false,
                'attr' => ['class' => 'bureau-field'],
            ])
            ->add('immeubleMixte', CheckboxType::class, [
                'label' => 'Immeuble Mixte',
                'required' => false,
                'attr' => ['class' => 'bureau-field'],
            ])
            ->add('ascenseur', CheckboxType::class, [
                'label' => 'Ascenseur',
                'required' => false,
                'attr' => ['class' => 'bureau-field'],
            ])
            ->add('chauffage', CheckboxType::class, [
                'label' => 'Chauffage',
                'required' => false,
                'attr' => ['class' => 'bureau-field'],
            ])
            ->add('climatisation', CheckboxType::class, [
                'label' => 'Climatisation',
                'required' => false,
                'attr' => ['class' => 'bureau-field'],
            ])
            ->add('entrepot', CheckboxType::class, [
                'label' => 'Entrepôt',
                'required' => false,
                'attr' => ['class' => 'bureau-field'],
            ])
            ->add('charges', IntegerType::class, [
                'label' => 'Charges',
                'required' => false,
                'attr' => ['class' => 'bureau-field'],
            ]);

        // Magasin specific fields
        $builder
            ->add('surface', null, [
                'label' => 'Surface (m²)',
                'attr' => ['class' => 'magasin-field'],
            ])
            ->add('nombreNiveaux', IntegerType::class, [
                'label' => 'Nombre de niveaux',
                'required' => false,
                'attr' => ['class' => 'magasin-field'],
            ])
            ->add('neuf', CheckboxType::class, [
                'label' => 'Neuf',
                'required' => false,
                'attr' => ['class' => 'magasin-field'],
            ])
            ->add('aRenover', CheckboxType::class, [
                'label' => 'À rénover',
                'required' => false,
                'attr' => ['class' => 'magasin-field'],
            ])
            ->add('doubleVitrine', CheckboxType::class, [
                'label' => 'Double vitrine',
                'required' => false,
                'attr' => ['class' => 'magasin-field'],
            ])
            ->add('extractionFumee', CheckboxType::class, [
                'label' => 'Extraction fumée',
                'required' => false,
                'attr' => ['class' => 'magasin-field'],
            ])
            ->add('climatisation', CheckboxType::class, [
                'label' => 'Climatisation',
                'required' => false,
                'attr' => ['class' => 'magasin-field'],
            ])
            ->add('chauffage', CheckboxType::class, [
                'label' => 'Chauffage',
                'required' => false,
                'attr' => ['class' => 'magasin-field'],
            ])
            ->add('charges', IntegerType::class, [
                'label' => 'Charges',
                'required' => false,
                'attr' => ['class' => 'magasin-field'],
            ])
            ->add('dpeKwh', IntegerType::class, [
                'label' => 'Consommation (kWh/m²/an)',
                'required' => false,
                'attr' => ['class' => 'magasin-field'],
            ])
            ->add('dpeCo2', IntegerType::class, [
                'label' => 'Émission (CO2/m²/an)',
                'required' => false,
                'attr' => ['class' => 'magasin-field'],
            ])
            ->add('wc', CheckboxType::class, [
                'label' => 'WC',
                'required' => false,
                'attr' => ['class' => 'magasin-field'],
            ])
            ->add('entrepot', CheckboxType::class, [
                'label' => 'Entrepôt',
                'required' => false,
                'attr' => ['class' => 'magasin-field'],
            ])
            ->add('detecteurIncendie', CheckboxType::class, [
                'label' => 'Détecteur incendie',
                'required' => false,
                'attr' => ['class' => 'magasin-field'],
            ])
            ->add('systemeSecurite', CheckboxType::class, [
                'label' => 'Système de sécurité',
                'required' => false,
                'attr' => ['class' => 'magasin-field'],
            ])
            ->add('dateConstruction', DateType::class, [
                'label' => 'Construit en',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'magasin-field'],
            ])
            ->add('dateRenovation', DateType::class, [
                'label' => 'Rénové en',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'magasin-field'],
            ]);
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