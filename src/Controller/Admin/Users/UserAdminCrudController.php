<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

use App\Entity\User\UserAdministrator;
use App\Enum\Role;
use App\Helper\GeneratePasswordHelper;
use App\Repository\User\UserAdministratorRepository;
use Doctrine\ORM\EntityManagerInterface;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMINISTRATOR')]
class UserAdminCrudController extends AbstractCrudController
{
    /**
     * @param GeneratePasswordHelper $generatePasswordHelper
     * @param Security $security
     * @param UserAdministratorRepository $administratorRepository
     */
    public function __construct(
        private readonly GeneratePasswordHelper $generatePasswordHelper,
        private readonly Security $security,
        private readonly UserAdministratorRepository $administratorRepository
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

        return $this->administratorRepository->getAdmin($user);
    }

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return UserAdministrator::class;
    }

    /**
     * @param Crud $crud
     *
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Administrateur')
            ->setEntityLabelInPlural('Administrateurs');
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
     *
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addColumn(6),
            TextField::new('firstName', 'Prénom'),
            EmailField::new('email', 'Email'),
            AssociationField::new('site', 'Site')
                ->formatValue(function ($value, $entity) {
                    return $entity->getSite()?->getName();
                }),

            FormField::addColumn(6),
            TextField::new('lastName', 'Nom'),
            ChoiceField::new('roles', 'Roles')
                ->setValue(Role::ROLE_ADMINISTRATOR->name)
                ->allowMultipleChoices()
                ->setChoices([
                    ucfirst(Role::ROLE_ADMINISTRATOR->value) => Role::ROLE_ADMINISTRATOR->name,
                ])->onlyOnDetail(),
        ];
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param mixed $entityInstance
     *
     * @return void
     * @throws TransportExceptionInterface
     */
    public function persistEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        /** @var UserAdministrator $entityInstance */
        if (!$entityInstance instanceof UserAdministrator) {
            return;
        }

        $this->generatePasswordHelper->createAccount($entityInstance);

        parent::persistEntity($entityManager, $entityInstance);
    }
}
