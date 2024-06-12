<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Enum\Calendar;
use RuntimeException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AirDatePickerType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'compound' => false,
            'widget' => 'single_text',
            'input' => 'datetime_immutable',
            'html5' => false,
            'data-view-inline' => false,
            'data-view-calendar' => Calendar::DAYS->name,
            'data-date-format' => 'yyyy-MM-dd',
            'data-min-date' => date('Y-m-d'),
            'data-max-date' => null,
            'data-range' => false,
            'data-timepicker' => false,
        ]);

        $resolver->setAllowedTypes('data-view-inline', ['false', 'boolean']);
        $resolver->setAllowedTypes('data-view-calendar', ['null', 'string']);
        $resolver->setAllowedTypes('data-date-format', ['null', 'string']);
        $resolver->setAllowedTypes('data-min-date', ['null', 'string']);
        $resolver->setAllowedTypes('data-max-date', ['null', 'string']);
        $resolver->setAllowedTypes('data-range', ['false', 'boolean']);
        $resolver->setAllowedTypes('data-timepicker', ['false', 'boolean']);

        $resolver->setNormalizer('data-view-calendar', static function (Options $options, $viewCalendar) {
            if (null === $viewCalendar) {
                return Calendar::DAYS->value;
            }

            return match ($viewCalendar) {
                Calendar::DAYS->name => Calendar::DAYS->value,
                Calendar::MONTHS->name => Calendar::MONTHS->value,
                default => throw new RunTimeException('View calendar unknown.'),
            };
        });
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);

        $view->vars['attr']['autocomplete'] = 'off';

        if (array_key_exists('class', $view->vars['attr'])) {
            $view->vars['attr']['class'] .= ' airdpcalendar';
        } else {
            $view->vars['attr']['class'] = 'airdpcalendar';
        }

        if (null !== $options['data-view-inline']) {
            $view->vars['attr']['data-view-inline'] = $options['data-view-inline'];
        }

        if (null !== $options['data-range']) {
            $view->vars['attr']['data-range'] = $options['data-range'];
        }

        if (null !== $options['data-timepicker']) {
            $view->vars['attr']['data-timepicker'] = $options['data-timepicker'];
        }

        if (null !== $options['data-view-calendar']) {
            $view->vars['attr']['data-view-calendar'] = $options['data-view-calendar'];
        }

        if (null !== $options['data-date-format']) {
            $view->vars['attr']['data-date-format'] = $options['data-date-format'];
        }

        if (null !== $options['data-min-date']) {
            $view->vars['attr']['data-min-date'] = $options['data-min-date'];
        }

        if (null !== $options['data-max-date']) {
            $view->vars['attr']['data-max-date'] = $options['data-max-date'];
        }
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return DateType::class;
    }
}
