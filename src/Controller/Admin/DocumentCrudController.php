<?php

namespace App\Controller\Admin;

use App\Entity\Document;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;

class DocumentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Document::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Document')
            ->setEntityLabelInPlural('Documents')
            ->setSearchFields(['title', 'filePath'])
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des Documents')
            ->setPageTitle(Crud::PAGE_NEW, 'Créer un nouveau Document')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier le Document');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre du Document'),
            Field::new('file')->setFormType(VichFileType::class)->onlyOnForms()->setLabel('Télécharger un Document'),
            TextField::new('filePath', 'Chemin du Fichier')->onlyOnIndex()->setLabel('Lien vers le Fichier'),
            DateTimeField::new('uploadedAt', 'Date de Téléchargement')->onlyOnIndex(),
        ];
    }
}
