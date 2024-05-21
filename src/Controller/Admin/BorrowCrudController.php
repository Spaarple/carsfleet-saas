<?php

namespace App\Controller\Admin;

use App\Entity\Borrow;
use App\Repository\BorrowRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMINISTRATOR')]
class BorrowCrudController extends AbstractCrudController
{
    /**
     * @param Security $security
     * @param BorrowRepository $borrowRepository
     */
    public function __construct(
        private readonly Security $security,
        private readonly BorrowRepository $borrowRepository
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

        return $this->borrowRepository->getBorrowByUser($user);
    }

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Borrow::class;
    }

    /**
     * @param Crud $crud
     *
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Emprunt')
            ->setEntityLabelInPlural('Emprunts')
            ->showEntityActionsInlined();
    }

    /**
     * @param Actions $actions
     *
     * @return Actions
     */
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE);
    }


    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('startDate', 'Date de début'),
            DateTimeField::new('endDate', 'Date de fin'),
            AssociationField::new('driver', 'Conducteur')
                ->formatValue(function ($value, $entity) {
                    $users = $entity->getUserEmployed()->first();
                    return sprintf('%s %s', $users->getFirstName(), $users->getLastName());
                }),
            AssociationField::new('car', 'Voiture')
                ->formatValue(function ($value, $entity) {
                    return sprintf('%s - %s',
                        $entity->getCar()->getBrand(),
                        $entity->getCar()->getModel()
                    );
                }),

            CollectionField::new('userEmployed', 'Passagers')
                ->formatValue(function ($value, $entity) {
                    $users = $entity->getUserEmployed();
                    $passengers = [];
                    foreach ($users as $user) {
                        $passengers[] = sprintf('%s %s', $user->getFirstName(), $user->getLastName());
                    }
                    return implode(', ', $passengers);
                }),
            AssociationField::new('borrowMeet', 'Lieu de départ')
                 ->formatValue(function ($value, $entity) {
                     return sprintf('%s', $entity->getBorrowMeet()->getSite()->getName());
                 }),
            AssociationField::new('borrowMeet', 'Destination')
                ->formatValue(function ($value, $entity) {
                    return sprintf('%s', $entity->getBorrowMeet()->getTripDestination()->getName());
                }),
        ];
    }
}
