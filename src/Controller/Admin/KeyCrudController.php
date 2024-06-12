<?php

namespace App\Controller\Admin;

use App\Entity\Key;
use App\Entity\User\UserAdministratorHeadOffice;
use App\Entity\User\UserAdministratorSite;
use App\Enum\Role;
use App\Enum\StatusKey;
use App\Form\Admin\Field\EnumField;
use App\Repository\KeyRepository;
use Doctrine\ORM\EntityRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMINISTRATOR_SITE')]
class KeyCrudController extends AbstractCrudController
{
    /**
     * @param Security $security
     * @param KeyRepository $keyRepository
     */
    public function __construct(
        private readonly Security $security,
        private readonly KeyRepository $keyRepository
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
            return $this->keyRepository->createQueryBuilder('k');
        }

        return $this->keyRepository->getKeysByUser($user);
    }

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Key::class;
    }

    /**
     * @param Crud $crud
     *
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Clé')
            ->setEntityLabelInPlural('Clés')
            ->showEntityActionsInlined();
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
        $user = $this->security->getUser();

        return [
            TextField::new('name', 'Nom de la clé'),
            AssociationField::new('car', 'Clé de la voiture')
                ->setFormTypeOptions([
                    'query_builder' => function (EntityRepository $er) use ($user) {
                        if ($user instanceof UserAdministratorHeadOffice) {
                            return $er->createQueryBuilder('c')
                                ->innerJoin('c.site', 's')
                                ->innerJoin('s.headOffice', 'h')
                                ->where('h.id = :headOfficeId')
                                ->setParameter(
                                    'headOfficeId', $user->getHeadOffice()?->getId(), UuidType::NAME
                                );
                        }

                        if ($user instanceof UserAdministratorSite) {
                            return $er->createQueryBuilder('c')
                                ->innerJoin('c.site', 's')
                                ->where('s.id = :siteId')
                                ->setParameter('siteId', $user->getSite()?->getId(), UuidType::NAME);
                        }
                    },
                ])
                ->formatValue(function ($value, $entity) {
                    return sprintf('%s - %s',
                        $entity->getCar()->getBrand(),
                        $entity->getCar()->getModel(),
                    );
                }),
            EnumField::setEnumClass(StatusKey::class)::new('status', 'Ou trouver la clé'),
        ];
    }
}
