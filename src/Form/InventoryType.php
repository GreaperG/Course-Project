<?php

namespace App\Form;

use App\Entity\Inventory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InventoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('Title')
            ->add('Description')
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Equipment' => 'equipment',
                    'Tools' => 'tools',
                    'Books' => 'books',
                    'Furniture' => 'furniture',
                    'Other' => 'other',
                ],
                'placeholder' => 'Choose Category',
            ])
            ->add('isPublic')
            ->add('inventoryAttributes', CollectionType::class, [
                'entry_type' => InventoryAttributeType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'prototype_name' => '__name__',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inventory::class,
        ]);
    }
}
