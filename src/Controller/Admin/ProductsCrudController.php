<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Products::class;
    }

    /**
     * Configure the CRUD Products 
     *
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('Produits')
            ->setEntityLabelInSingular('Produit')
            ->setSearchFields(['name', 'volume', 'categories.id'])
            ->setDefaultSort(['name' => 'DESC']);
    }

    /**
     * Configuration of the fields 
     *
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('name'),
            MoneyField::new('price')->setCurrency('EUR'),
            Field::new('ingredients'),
            Field::new('volume'),
            TextField::new('imageFile')->setFormType(VichImageType::class)->onlyWhenCreating(),
            ImageField::new('file')->setBasePath('products')->onlyOnIndex(),
            AssociationField::new('categories'),
        ];
    }
    
}
