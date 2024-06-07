<?php

namespace App\Form;

use App\Entity\Borrow;
use App\Form\Type\AirDatePickerType;
use Symfony\Component\Form\AbstractType;
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
            ->add('startDate', AirDatePickerType::class, [
                'label' => 'Date souhaité de l\'emprunt',
                'widget' => 'single_text',
                'compound' => false,
                'input' => 'datetime_immutable',
                'required' => true,
                'data-range' => true,
                'data-timepicker' => true,
                'data-view-inline' => true,
                'data-min-date' => date('Y-m-d'),
                'data-max-date' => date('Y-m-d', strtotime('+6 months')),
                'attr' => [
                    'placeholder' => '01/01/2025',
                    'class' => 'mb-5'
                ],
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
            'error_bubbling' => true,
        ]);
    }
}
