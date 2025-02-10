<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\VichImageField;




class PropertyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Property::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),                 // "id": 1
            TextField::new('type', 'Type de bien'),                 // "type": "Appartement"

            // Field for image uploads
            TextField::new('imageFiles', 'Images')
                ->setFormType(VichImageType::class)
                ->setHelp('Upload images for the property')
                ->onlyOnForms(),

            // Display uploaded images
            ImageField::new('img', 'Uploaded Images')
                ->setBasePath('/uploads/images')
                ->setUploadDir('public/uploads/images')
                ->setHelp('Images uploaded for this property')
                ->onlyOnIndex(),


            TextField::new('thumbnail', 'Miniature')->onlyOnForms(),               // "thumbnail": "https://example.com/thumb1.jpg"
            TextField::new('propertyStatus', 'Statut du bien'),     // "propertyStatus": "À Vendre"
            TextField::new('country', 'Pays')->onlyOnForms(),                      // "country": "Maroc"
            TextField::new('city', 'Ville'),
            TextField::new('neighborhood', 'Quartier'),             // "quartier": "Maarif"
            TextField::new('title', 'Titre'),                       // "title": "Appartement Moderne à Casablanca"
            NumberField::new('price', 'Prix'),                      // "price": 950000
            TextareaField::new('description', 'Détails'),           // "details": "Un appartement de luxe situé au cœur de Casablanca"
            TextField::new('home', 'Maison'),                       // "home": "2CH"
            TextField::new('bed', 'Lits'),                          // "bed": "2"
            TextField::new('bath', 'Salles de bain'),               // "bath": "2"
            NumberField::new('rooms', 'Pièces'),                    // "rooms": 4
            NumberField::new('surface', 'Surface (m²)'),
            DateTimeField::new('createdAt', 'Date de création'),    // "date": "2024-10-01"
            TextField::new('propertyType', 'Type de bien'),         // "propertyType": "Condo"
            AssociationField::new('agence', 'Agence'),              // "agencies": "Immobilier Prestige"

            // CollectionField for labels
            CollectionField::new('labels', 'Étiquettes')
                ->setEntryType(TextType::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->allowAdd()
                ->allowDelete(),                                    // "labels": ["Luxe", "Moderne"]

            BooleanField::new('sale', 'À vendre'),                  // "sale": true
                        // "surface": 1100
        ];
    }
}
