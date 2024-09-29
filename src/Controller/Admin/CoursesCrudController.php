<?php

namespace App\Controller\Admin;

use App\Entity\Courses;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CoursesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Courses::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('sessionName'),
            TextField::new('pinCode'),
            BooleanField::new('active'),
            AssociationField::new('user', 'User'),
        ];
    }
    
}
