<?php

namespace App\Form;

use App\Entity\Borrow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BorrowFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateTimeType::class, [
                'label' => 'Date de début de l\'emprunt',
                'widget' => 'single_text',
                'compound' => false,
                'input' => 'datetime_immutable',
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Date de fin de l\'emprunt',
                'widget' => 'single_text',
                'compound' => false,
                'input' => 'datetime_immutable',
            ])
            ->add('borrowMeet', BorrowMeetFormType::class, [
                'label' => 'Rendez-vous',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Borrow::class,
        ]);
    }
}
