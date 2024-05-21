<?php

namespace App\Form;

use App\Entity\BorrowMeet;
use App\Entity\Site;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BorrowMeetFormType extends AbstractType
{

    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder
            ->add('date', DateTimeType::class, [
                'label' => 'Choisir la date et l\'heure du rendez-vous',
                'widget' => 'single_text',
                'compound' => false,
                'input' => 'datetime_immutable',
            ])
            ->add('tripDestination', EntityType::class, [
                'label' => 'Choisir le site de destination',
                'class' => Site::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $entityRepository) use ($user) {
                    return $entityRepository->createQueryBuilder('s')
                        ->where('s.headOffice = :headOffice')
                        ->setParameter('headOffice', $user->getSite()->getHeadOffice()?->getId(),
                    UuidType::NAME);
                },
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BorrowMeet::class,
        ]);
    }
}
