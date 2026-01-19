<?php

namespace App\Form;

use App\Entity\InventoryAttribute;
use App\Enum\AttributeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InventoryAttributeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('type',EnumType::class,[
                'class' => AttributeType::class,
                'choice_label' => function ($choice, $key, $value) {
                return $choice->value;
                }
            ])
            ->add('required');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InventoryAttribute::class,
        ]);
    }
}
