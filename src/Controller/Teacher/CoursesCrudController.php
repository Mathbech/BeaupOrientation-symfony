<?php

namespace App\Controller\Teacher;

use App\Entity\Courses;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Symfony\Bundle\SecurityBundle\Security;

class CoursesCrudController extends AbstractCrudController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    public static function getEntityFqcn(): string
    {
        return Courses::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $fields = [
            TextField::new('sessionName'),
            TextField::new('pinCode')->hideOnForm(),
            BooleanField::new('active')->hideOnForm(),
            DateTimeField::new('createdAt')->hideOnForm(),
        ];

        // Ajouter le champ `user`, caché dans le formulaire mais avec la valeur utilisateur courante
        // $userField = AssociationField::new('user')
        //     ->setFormTypeOption('attr', ['hidden' => true]) // Masquer le champ dans le formulaire
        //     ->setFormTypeOption('data', $this->security->getUser()) // Prendre la valeur de l'utilisateur connecté
        //     ->hideOnIndex(); // Cacher le champ dans l'index/liste

        $userField = AssociationField::new('user')
            ->setFormTypeOption('attr', ['hidden' => true]) // Masquer le champ dans le formulaire
            ->setFormTypeOption('data', $this->security->getUser()) // Assigner l'utilisateur connecté
            ->setQueryBuilder(function ($queryBuilder) {
                $queryBuilder->andWhere('entity = :currentUser') // Utilise 'entity' pour référencer la table de l'entité associée
                    ->setParameter('currentUser', $this->security->getUser())
                    ->setMaxResults(1); // Limite la requête à l'utilisateur connecté uniquement
            });



        $fields[] = $userField;

        return $fields;
    }
}
