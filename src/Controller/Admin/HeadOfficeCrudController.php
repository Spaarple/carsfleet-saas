<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\HeadOffice;
use App\Entity\User\UserSuperAdministrator;
use App\Repository\HeadOfficeRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMINISTRATOR_HEAD_OFFICE')]
class HeadOfficeCrudController extends AbstractCrudController
{
    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return HeadOffice::class;
    }

    /**
     * @param Security $security
     * @param HeadOfficeRepository $headOfficeRepository
     */
    public function __construct(
        private readonly Security $security,
        private readonly HeadOfficeRepository $headOfficeRepository
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

        if(!$user instanceof UserSuperAdministrator) {
            $this->redirectToRoute('admin');
        }

        return $this->headOfficeRepository->getHeadOfficeByUser($user);
    }

    /**
     * @param Crud $crud
     *
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Siège Social')
            ->setPageTitle(Crud::PAGE_INDEX, 'Siège Social')
            ->showEntityActionsInlined()
        ->setPageTitle('index', 'Siège social')
        ->setPageTitle('detail', 'Détail du siège social')
        ->setPageTitle('edit', 'Modification du siège social');
    }

    /**
     * @param Actions $actions
     * @return Actions
     */
    public function configureActions(Actions $actions): Actions
    {
        $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE);

        if ($this->isGranted('ROLE_ADMINISTRATOR')) {
            $actions->remove(Crud::PAGE_DETAIL, Action::EDIT);
            $this->redirectToRoute('admin');
        }

        if ($this->isGranted('ROLE_SUPER_ADMINISTRATOR')) {
            $actions->add(Crud::PAGE_DETAIL, Action::EDIT);
            $this->redirectToRoute('admin');
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
            TextField::new('name', 'Nom du siège social'),
            TextField::new('address', 'Adresse'),
            TextField::new('postalCode', 'Code Postal'),
            CountryField::new('country', 'Pays'),
            TextField::new('region', 'Région'),
        ];
    }
}
