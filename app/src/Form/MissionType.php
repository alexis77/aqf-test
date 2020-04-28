<?php

namespace App\Form;

use App\Entity\Mission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class MissionType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serviceDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('productName')
            ->add('quantity', IntegerType::class, [
                'constraints' => [
                    new Assert\PositiveOrZero(),
                ],
            ])
            ->add('destinationCountry', CountryType::class)
            ->add('vendorName', TextType::class, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[a-z0-9\-\_]+$/i',
                        'message' => 'The value should be alpha numeric',
                    ]),
                ],
            ])
            ->add('vendorEmail', EmailType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
