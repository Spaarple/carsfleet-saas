<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User\AbstractUser;
use App\Enum\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'disabled' => true,
            ])
            ->add('matricule', NumberType::class, [
                'label' => 'Matricule',
                'disabled' => true,
            ])
            ->add('service', EnumType::class, [
                'label' => 'Service',
                'class' => Service::class,
                'disabled' => true,
            ])
            ->add('site', EntityType::class, [
                'label' => 'Site',
                'class' => Site::class,
                'disabled' => true,
            ])
            ->add('drivingLicense', CheckboxType::class, [
                'label'    => 'Permis de conduire',
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
            'data_class' => AbstractUser::class,
        ]);
    }
}