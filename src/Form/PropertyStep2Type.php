<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyStep2Type extends AbstractType
{
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
        $propertyType = $options['property_type'];

        switch ($propertyType) {
            case 'appartement':
                $this->buildAppartementForm($builder);
                break;
            case 'maison':
            case 'villa':
                $this->buildMaisonVillaForm($builder);
                break;
            case 'bureau':
                $this->buildBureauForm($builder);
                break;
            case 'magasin':
                $this->buildMagasinForm($builder);
                break;
        }
    }

    private function buildAppartementForm(FormBuilderInterface $builder): void
    {
        $builder
            ->add('rooms', IntegerType::class, [
                'label' => 'Chambres',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('bath', IntegerType::class, [
                'label' => 'Salles de bain',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('nbrEtage', ChoiceType::class, [
                'label' => 'Numéro d\'étage',
                'placeholder' => 'Numéro d\'étage',
                'choices' => $this->etage,
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('meubler', CheckboxType::class, [
                'label' => 'Meublé',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('concierge', CheckboxType::class, [
                'label' => 'Concierge',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('residenceSurveillee', CheckboxType::class, [
                'label' => 'Résidence surveillée',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('charges', IntegerType::class, [
                'label' => 'Charges',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('etat', ChoiceType::class, [
                'label' => 'État',
                'choices' => $this->etatChoices,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Sélectionnez un état',
            ])
            ->add('orientation', ChoiceType::class, [
                'label' => 'Orientation',
                'choices' => $this->orientationChoices,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Sélectionnez une orientation',
            ])
            ->add('chauffageIndividuel', CheckboxType::class, [
                'label' => 'Chauffage individuel',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('hauteur', IntegerType::class, [
                'label' => 'Hauteur (cm)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('ascenseur', CheckboxType::class, [
                'label' => 'Ascenseur',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('facadePrincipale', ChoiceType::class, [
                'label' => 'Façade principale',
                'choices' => $this->facadeChoices,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Sélectionnez une façade',
            ])
            ->add('climatisation', CheckboxType::class, [
                'label' => 'Climatisation',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('dpeKwh', IntegerType::class, [
                'label' => 'Diagnostic de performance énergétique (kWh/m²/an)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    private function buildMaisonVillaForm(FormBuilderInterface $builder): void
    {
        $builder
            ->add('rooms', IntegerType::class, [
                'label' => 'Chambres',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('bath', IntegerType::class, [
                'label' => 'Salles de bain',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('nombreNiveaux', IntegerType::class, [
                'label' => 'Nombre de niveaux',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('surfaceTerrain', IntegerType::class, [
                'label' => 'Surface terrain (m²)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('surfaceConstruite', IntegerType::class, [
                'label' => 'Surface construite (m²)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('terrasse', CheckboxType::class, [
                'label' => 'Terrasse',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('balcon', CheckboxType::class, [
                'label' => 'Balcon',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('parkingAbrite', CheckboxType::class, [
                'label' => 'Place de parking abritée',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('parkingExterieur', CheckboxType::class, [
                'label' => 'Place de parking extérieure',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('climatisation', CheckboxType::class, [
                'label' => 'Climatisation',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('piscine', CheckboxType::class, [
                'label' => 'Piscine',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('jardin', CheckboxType::class, [
                'label' => 'Jardin',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('energiePhotovoltaique', CheckboxType::class, [
                'label' => 'Énergie photovoltaïque',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('orientation', ChoiceType::class, [
                'label' => 'Orientation',
                'choices' => $this->orientationChoices,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Sélectionnez une orientation',
            ])
            ->add('chauffageIndividuel', CheckboxType::class, [
                'label' => 'Chauffage individuel',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('logeDomestique', CheckboxType::class, [
                'label' => 'Loge / Pièce domestique',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('debarras', CheckboxType::class, [
                'label' => 'Débarras',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('dpeKwh', IntegerType::class, [
                'label' => 'Diagnostic de performance énergétique (kWh/m²/an)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dpeCo2', IntegerType::class, [
                'label' => 'Diagnostic de performance énergétique (CO₂/m²/an)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateConstruction', DateType::class, [
                'label' => 'Date de construction',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateRenovation', DateType::class, [
                'label' => 'Date de dernière rénovation',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('camera', CheckboxType::class, [
                'label' => 'Caméra',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('alarme', CheckboxType::class, [
                'label' => 'Alarme',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('residenceFermee', CheckboxType::class, [
                'label' => 'Résidence fermée',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ]);
    }

    private function buildBureauForm(FormBuilderInterface $builder): void
    {
        $builder
            ->add('surfaceConstruite', IntegerType::class, [
                'label' => 'Surface Construite (m²)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('surfaceHabitable', IntegerType::class, [
                'label' => 'Surface Habitable (m²)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('openSpace', CheckboxType::class, [
                'label' => 'Open Space',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('cloisonDur', CheckboxType::class, [
                'label' => 'Cloison en Dur',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('parking', CheckboxType::class, [
                'label' => 'Place Parking',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('cuisine', CheckboxType::class, [
                'label' => 'Cuisine',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('wc', CheckboxType::class, [
                'label' => 'WC',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('nbrEtage', ChoiceType::class, [
                'label' => 'Étage',
                'placeholder' => 'Sélectionnez un étage',
                'choices' => $this->etage,
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('immeubleBureau', CheckboxType::class, [
                'label' => 'Immeuble Bureau',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('immeubleMixte', CheckboxType::class, [
                'label' => 'Immeuble Mixte',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('ascenseur', CheckboxType::class, [
                'label' => 'Ascenseur',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('chauffage', CheckboxType::class, [
                'label' => 'Chauffage',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('climatisation', CheckboxType::class, [
                'label' => 'Climatisation',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('entrepot', CheckboxType::class, [
                'label' => 'Entrepôt',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('charges', IntegerType::class, [
                'label' => 'Charges',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    private function buildMagasinForm(FormBuilderInterface $builder): void
    {
        $builder
            ->add('nombreNiveaux', IntegerType::class, [
                'label' => 'Nombre de niveaux',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('neuf', CheckboxType::class, [
                'label' => 'Neuf',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('aRenover', CheckboxType::class, [
                'label' => 'À rénover',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('doubleVitrine', CheckboxType::class, [
                'label' => 'Double vitrine',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('extractionFumee', CheckboxType::class, [
                'label' => 'Extraction fumée',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('climatisation', CheckboxType::class, [
                'label' => 'Climatisation',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('chauffage', CheckboxType::class, [
                'label' => 'Chauffage',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('charges', IntegerType::class, [
                'label' => 'Charges',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dpeKwh', IntegerType::class, [
                'label' => 'Consommation (kWh/m²/an)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dpeCo2', IntegerType::class, [
                'label' => 'Émission (CO2/m²/an)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('wc', CheckboxType::class, [
                'label' => 'WC',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('entrepot', CheckboxType::class, [
                'label' => 'Entrepôt',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('detecteurIncendie', CheckboxType::class, [
                'label' => 'Détecteur incendie',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('systemeSecurite', CheckboxType::class, [
                'label' => 'Système de sécurité',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('dateConstruction', DateType::class, [
                'label' => 'Construit en',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateRenovation', DateType::class, [
                'label' => 'Rénové en',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'property_type' => null,
        ]);
    }
}