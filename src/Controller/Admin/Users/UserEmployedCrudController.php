<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

use App\Entity\User\UserEmployed;
use App\Enum\Role;
use App\Enum\Service;
use App\Form\Admin\Field\EnumField;
use App\Helper\GeneratePasswordHelper;
use App\Repository\User\UserEmployedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ArrayFilter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMINISTRATOR_SITE')]
class UserEmployedCrudController extends AbstractCrudController
{

    /**
     * @param GeneratePasswordHelper $generatePasswordHelper
     * @param Security $security
     * @param UserEmployedRepository $userEmployedRepository
     */
    public function __construct(
        private readonly GeneratePasswordHelper $generatePasswordHelper,
        private readonly Security $security,
        private readonly UserEmployedRepository $userEmployedRepository
    ) {
    }

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
            return $this->userEmployedRepository->createQueryBuilder('u');
        }

        return $this->userEmployedRepository->getEmployees($user);
    }

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return UserEmployed::class;
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
            ->setEntityLabelInSingular('Employé')
            ->setEntityLabelInPlural('Employés');
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
     * @param Filters $filters
     *
     * @return Filters
     */
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ArrayFilter::new('roles')->setChoices(Role::asArrayInverted()));
    }

    /**
     * @param string $pageName
     *
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addColumn(6),
            TextField::new('firstName', 'Prénom'),
            TextField::new('lastName', 'Nom'),
            AssociationField::new('site', 'Site')
                ->formatValue(function ($value, $entity) {
                    return $entity->getSite()?->getName();
                }),
            NumberField::new('matricule', 'Matricule')
                ->setThousandsSeparator('-'),
            BooleanField::new('drivingLicense', 'Permis de conduire (B)')->setDisabled(),

            FormField::addColumn(6),
            EmailField::new('email', 'Email'),
            EnumField::setEnumClass(Service::class)::new('service', 'Service'),
        ];
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param mixed $entityInstance
     * @return void
     * @throws TransportExceptionInterface
     */
    public function persistEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        /** @var UserEmployed $entityInstance */
        if (!$entityInstance instanceof UserEmployed) {
            return;
        }

        $this->generatePasswordHelper->createAccount($entityInstance);

        parent::persistEntity($entityManager, $entityInstance);
    }
}
