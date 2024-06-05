<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use App\Enum\Fuel;
use App\Enum\GearBox;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
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
            ->showEntityActionsInlined()
            ->setEntityLabelInSingular('Voiture')
            ->setPageTitle(
                'detail',
                fn (Car $car) => sprintf(
                    '%s %s : %s',
                    $car->getBrand(),
                    $car->getModel(),
                    $car->getRegistrationNumber()
                )
            )
            ->setPageTitle(
                'edit',
                fn (Car $car) => sprintf(
                    '%s %s : %s',
                    $car->getBrand(),
                    $car->getModel(),
                    $car->getRegistrationNumber()
                )
            )
            ->setEntityLabelInPlural('Voitures');
    }

    /**
     * @param Actions $actions
     *
     * @return Actions
     */
    public function configureActions(Actions $actions): Actions
    {
        $edit = Action::new('edit-custom', '')
            ->setIcon('fa fa-pencil')
            ->linkToCrudAction(Crud::PAGE_EDIT);

        $view = Action::new('view-custom', '')
            ->setIcon('fa fa-eye')
            ->linkToCrudAction(Crud::PAGE_DETAIL);

        return $actions
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_INDEX, $edit)
            ->add(Crud::PAGE_INDEX, $view);
    }

    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Informations Générales')->setIcon('info'),
            FormField::addColumn(6)->hideOnIndex(),
            TextField::new('brand', 'Marque'),
            TextField::new('model', 'Modèle'),
            NumberField::new('passengerQuantity', 'Qté passagers')
                ->setTextAlign('center'),
            NumberField::new('kilometers', 'Kilométrage')
                ->setTextAlign('center')->hideOnIndex(),

            TextField::new('registrationNumber', 'N° d\'immatriculation')
                ->setTextAlign('center'),

            DateTimeField::new('circulationDate', 'Date de mise en circulation')
                ->setFormat('dd/MM/yyyy')
                ->setTextAlign('center')->hideOnIndex(),
            DateTimeField::new('yearOfProduction', 'Date de fabrication')
                ->setFormat('dd/MM/yyyy')
                ->setTextAlign('center')->hideOnIndex(),
            ColorField::new('color', 'Couleur'),
            CollectionField::new('carKeys', 'Nbre de clés')
                ->setTextAlign('center')
                ->hideOnForm()->hideOnIndex()
                ->formatValue(function ($value, $entity) {
                    return $entity->getCarKeys()->count();
                }),
            EnumField::setEnumClass(Fuel::class)::new('fuel', 'Carburant')
                ->hideOnIndex()->setTemplatePath('admin/badge.html.twig'),
            EnumField::setEnumClass(GearBox::class)::new('gearbox', 'Boîte de vitesse')
                ->hideOnIndex()->setTemplatePath('admin/badge.html.twig'),

            FormField::addColumn(6)->hideOnIndex(),
            EnumField::setEnumClass(StatusCars::class)::new('status', 'Statut')
                ->setTemplatePath('admin/badge.html.twig'),
            TextField::new('serialNumber', 'N° de série')->hideOnIndex(),
            AssociationField::new('site', 'Site')
                ->formatValue(function ($value, $entity) {
                    return $entity->getSite()?->getName();
                }),
            NumberField::new('fiscalHorsePower', 'Puissance Fiscale')
                ->setTextAlign('center')->hideOnIndex(),
            NumberField::new('horsePower', 'Puissance')
                ->setTextAlign('center')->hideOnIndex(),

            FormField::addTab('Carnet')->setIcon('car'),
            CollectionField::new('accidents', '')
                ->onlyOnDetail()
                ->setTemplatePath('admin/accidents.html.twig')
                ->formatValue(function ($value, $entity) {
                    return $entity->getAccidents();
                }),
        ];
    }
}
