<?php

declare(strict_types=1);

namespace App\Form;

use App\DTO\SearchCarDTO;
use App\Enum\GearBox;
use App\Form\Type\AirDatePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('from', AirDatePickerType::class, [
                'label' => false,
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Date de début',
                ],
                'compound' => false,
                'input' => 'datetime_immutable',
                'data-range' => false,
                'data-timepicker' => false,
                'data-view-inline' => false,
                'data-min-date' => date('Y-m-d'),
                'data-max-date' => date('Y-m-d', strtotime('+3 months')),
            ])
            ->add('to', AirDatePickerType::class, [
                'label' => false,
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Date de fin',
                ],
                'compound' => false,
                'input' => 'datetime_immutable',
                'data-range' => false,
                'data-timepicker' => false,
                'data-view-inline' => false,
                'data-min-date' => date('Y-m-d'),
                'data-max-date' => date('Y-m-d', strtotime('+3 months')),
            ])
            ->add('gearbox', EnumType::class, [
                'class' => GearBox::class,
                'choice_label' => 'value',
                'choice_value' => 'name',
                'label' => 'Transmission',
                'placeholder' => 'Choisir la boîte de vitesse...',
                'required' => false,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchCarDTO::class,
            'method' => 'GET',
            'csrf_protection' => false,
            'placeholder' => '',
        ]);

        $resolver->setAllowedTypes('placeholder', 'string');
    }
}
