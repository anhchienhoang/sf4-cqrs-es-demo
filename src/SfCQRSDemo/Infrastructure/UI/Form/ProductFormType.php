<?php

namespace SfCQRSDemo\Infrastructure\UI\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductFormType extends AbstractType
{
    const
        NAME = 'name',
        PRICE = 'price',
        DESCRIPTION = 'description';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                static::NAME,
                TextType::class,
                [
                    'label' => 'name',
                    'constraints' => new NotBlank(),
                ]
            )
            ->add(
                static::PRICE,
                MoneyType::class,
                [
                    'label' => 'price',
                    'constraints' => new NotBlank(),
                ]
            )
            ->add(
                static::DESCRIPTION,
                TextareaType::class,
                [
                    'label' => 'description',
                    'constraints' => new NotBlank(),
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => $options['submitLabel'],
                    'attr' => ['class' => 'btn-primary']
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'submitLabel' => 'add_new_product',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'product_form';
    }
}
