<?php

namespace App\Controller\Admin;

use App\Entity\Site;
use App\Enum\Role;
use App\Repository\SiteRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMINISTRATOR_SITE')]
class SiteCrudController extends AbstractCrudController
{

    /**
     * @param Security $security
     * @param SiteRepository $siteRepository
     */
    public function __construct(
        private readonly Security $security,
        private readonly SiteRepository $siteRepository
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
            return $this->siteRepository->createQueryBuilder('s');
        }

        return $this->siteRepository->getSitesByHeadOffice($user);
    }

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Site::class;
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
            ->setEntityLabelInSingular('Sites');
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

        $actions
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_INDEX, $edit)
            ->add(Crud::PAGE_INDEX, $view);

        if (!$this->isGranted(Role::ROLE_ADMINISTRATOR_HEAD_OFFICE->name)) {
            $actions->remove(Crud::PAGE_INDEX, Action::DELETE);
        }

        return $actions;
    }

    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addColumn(6),
            TextField::new('name', 'Nom'),
            TextField::new('address', 'Adresse'),
            TextField::new('postalCode', 'Code Postal'),
            FormField::addColumn(6),
            CountryField::new('country', 'Pays'),
            TextField::new('region', 'Région'),
            AssociationField::new('headOffice', 'Siège Social')
                ->formatValue(function ($value, $entity) {
                    return sprintf('%s',
                        $entity->getHeadOffice()->getName(),
                    );
                }),
        ];
    }

}
