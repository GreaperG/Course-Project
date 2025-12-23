<?php

namespace App\Form;

use App\Entity\Inventory;
use App\Entity\Item;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customId', TextType::class, [
                'label' => 'Custom ID',
                'help' => 'Unique identifier for this item',
                'attr' => [
                    'placeholder' => 'e.g. LAPTOP-001'
                ]
            ])
            ->add('inventory', EntityType::class, [
                'class' => Inventory::class,
                'choice_label' => 'title',
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
