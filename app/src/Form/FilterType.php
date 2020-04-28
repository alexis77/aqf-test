<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class FilterType extends AbstractType
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if ($this->security->isGranted([User::ROLE_ADMIN])) {
            $builder
                ->add('name', TextType::class, [
                    'required' => false,
                    'label' => 'Client name'
                ]);
        }

        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Service date',
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('productName', TextType::class, [
                'required' => false,
            ])
            ->add('vendorName', TextType::class, [
                'required' => false,
            ])
            ->add('vendorEmail', EmailType::class, [
                'required' => false,
            ])
            ->add('destinationCountry', CountryType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
