<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    private  $marocCity =  [
        'Casablanca' => 'Casablanca',
        'Rabat' => 'Rabat',
        'Fès' => 'Fès',
        'Marrakech' => 'Marrakech',
        'Tanger' => 'Tanger',
        'Agadir' => 'Agadir',
        'Meknès' => 'Meknès',
        'Oujda' => 'Oujda',
        'Kenitra' => 'Kenitra',
        'Tétouan' => 'Tétouan',
        'Salé' => 'Salé',
        'Nador' => 'Nador',
        'Laâyoune' => 'Laâyoune',
        'Safi' => 'Safi',
        'Mohammédia' => 'Mohammédia',
        'Khouribga' => 'Khouribga',
        'El Jadida' => 'El Jadida',
        'Beni Mellal' => 'Beni Mellal',
        'Taza' => 'Taza',
        'Settat' => 'Settat',
        'Ksar El Kebir' => 'Ksar El Kebir',
        'Larache' => 'Larache',
        'Berkane' => 'Berkane',
        'Inezgane' => 'Inezgane',
        'Guelmim' => 'Guelmim',
        'Ouarzazate' => 'Ouarzazate',
        'Al Hoceïma' => 'Al Hoceïma',
        'Khenifra' => 'Khenifra',
        'Tiflet' => 'Tiflet',
        'Temara' => 'Temara',
        'Sidi Kacem' => 'Sidi Kacem',
        'Sidi Slimane' => 'Sidi Slimane',
        'Oued Zem' => 'Oued Zem',
        'Berrechid' => 'Berrechid',
        'Fnideq' => 'Fnideq',
        'Youssoufia' => 'Youssoufia',
        'Taourirt' => 'Taourirt',
        'Tan-Tan' => 'Tan-Tan',
        'Sefrou' => 'Sefrou',
        'Boujdour' => 'Boujdour',
        'Tinghir' => 'Tinghir',
        'Dakhla' => 'Dakhla',
        'Errachidia' => 'Errachidia',
        'Essaouira' => 'Essaouira',
        'Tiznit' => 'Tiznit',
        'Azilal' => 'Azilal',
        'Chichaoua' => 'Chichaoua',
        'Chefchaouen' => 'Chefchaouen',
        'Oulmes' => 'Oulmes',
        'Midelt' => 'Midelt',
    ];
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('city', ChoiceType::class, [
                'choices' => $this->marocCity,
                'label' => 'Ville',
                'attr' => ['class' => 'form-control'], // Add form-control class for styling
                'placeholder' => 'Sélectionnez une ville', // Optional: Placeholder text
            ])
            ->add('neighborhood', TextType::class, [
                'label' => 'Cartier',
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
