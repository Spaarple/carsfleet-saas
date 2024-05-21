<?php

namespace App\Controller\Admin;

use App\Entity\Key;
use App\Entity\User\UserAdministrator;
use App\Entity\User\UserSuperAdministrator;
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
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMINISTRATOR')]
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

        return $this->keyRepository->getKeysByUser($user);
    }

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
            ->setEntityLabelInPlural('Clés');
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
        $user = $this->security->getUser();

        return [
            AssociationField::new('car', 'Clé de la voiture')
                ->setFormTypeOptions([
                    'query_builder' => function (EntityRepository $er) use ($user) {
                        if ($user instanceof UserSuperAdministrator) {
                            return $er->createQueryBuilder('c')
                                ->innerJoin('c.site', 's')
                                ->innerJoin('s.headOffice', 'h')
                                ->where('h.id = :headOfficeId')
                                ->setParameter(
                                    'headOfficeId', $user->getHeadOffice()?->getId(), UuidType::NAME
                                );
                        }

                        if ($user instanceof UserAdministrator) {
                            return $er->createQueryBuilder('c')
                                ->innerJoin('c.site', 's')
                                ->where('s.id = :siteId')
                                ->setParameter('siteId', $user->getSite()?->getId(), UuidType::NAME);
                        }
                    },
                ])
                ->formatValue(function ($value, $entity) {
                    return sprintf('%s - %s',
                        $entity->getCar()->getModel(),
                        $entity->getCar()->getBrand(),
                    );
                }),
            EnumField::setEnumClass(StatusKey::class)::new('status', 'Ou trouver la clé'),
        ];
    }
}
