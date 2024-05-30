<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use App\Enum\Role;
use App\Enum\StatusCars;
use App\Form\Admin\Field\EnumField;
use App\Repository\CarRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMINISTRATOR_SITE')]
class CarCrudController extends AbstractCrudController
{

    /**
     * @param Security $security
     * @param CarRepository $carRepository
     */
    public function __construct(
        private readonly Security $security,
        private readonly CarRepository $carRepository
    )
    {}

    /**
     * @param SearchDto $searchDto
     * @param EntityDto $entityDto
     * @param FieldCollection $fields
     * @param FilterCollection $filters
     * @return QueryBuilder
     */
    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder
    {
        $user = $this->security->getUser();
        if (in_array(Role::ROLE_SUPER_ADMINISTRATOR->name, $user?->getRoles(), true)) {
            return $this->carRepository->createQueryBuilder('c');
        }

        return $this->carRepository->getCarsByUser($user);
    }

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Car::class;
    }

    /**
     * @param Crud $crud
     *
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Voiture')
            ->setEntityLabelInPlural('Voitures');
    }

    /**
     * @param Actions $actions
     *
     * @return Actions
     */
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }


    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addColumn(6)->hideOnIndex(),
            TextField::new('brand', 'Marque'),
            TextField::new('model', 'Modèle'),
            TextField::new('registrationNumber', 'N° d\'immatriculation')
                ->setTextAlign('center'),
            NumberField::new('passengerQuantity', 'Qté passagers')
                ->setTextAlign('center'),

            FormField::addColumn(6)->hideOnIndex(),
            EnumField::setEnumClass(StatusCars::class)::new('status', 'Statut'),
            TextField::new('serialNumber', 'N° de série'),
            AssociationField::new('site', 'Site')
                ->formatValue(function ($value, $entity) {
                    return $entity->getSite()?->getName();
                }),
            ColorField::new('color', 'Couleur'),
            CollectionField::new('carKeys', 'Nbre de clés')
                ->setTextAlign('center')
                ->hideOnForm()
                ->formatValue(function ($value, $entity) {
                    return $entity->getCarKeys()->count();
                }),
        ];
    }
}
