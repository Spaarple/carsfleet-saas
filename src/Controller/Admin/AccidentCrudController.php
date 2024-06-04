<?php

namespace App\Controller\Admin;

use App\Entity\Accident;
use App\Enum\Role;
use App\Repository\AccidentRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMINISTRATOR_SITE')]
class AccidentCrudController extends AbstractCrudController
{
    /**
     * @param Security $security
     * @param AccidentRepository $accidentRepository
     */
    public function __construct(
        private readonly Security $security,
        private readonly AccidentRepository $accidentRepository
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
            return $this->accidentRepository->createQueryBuilder('a');
        }

        return $this->accidentRepository->getAccidentByUser($user);
    }

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Accident::class;
    }

    /**
     * @param Crud $crud
     *
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Accident')
            ->setEntityLabelInPlural('Accidents')
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
            DateTimeField::new('date', 'Date de l\'accident')->setFormat('dd-MM-yyyy HH:mm'),
            AssociationField::new('car', 'Voiture engagée dans l\'accident')
                ->formatValue(function ($value, $entity) {
                    return sprintf('%s - %s',
                        $entity->getCar()->getBrand(),
                        $entity->getCar()->getModel()
                    );
                }),
            TextField::new('description', 'Description de l\'accident')->onlyOnDetail(),
            AssociationField::new('userEmployed', 'Employé impliqué dans l\'accident')
                ->formatValue(function ($value, $entity) {
                    return sprintf('%s %s',
                        $entity->getUserEmployed()->getFirstName(),
                        $entity->getUserEmployed()->getLastName(),
                    );
            }),
        ];
    }
}
