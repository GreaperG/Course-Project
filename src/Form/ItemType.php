<?php

namespace App\Form;

use App\Entity\Item;
use App\Enum\AttributeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customId', TextType::class, [
                'required' => false,
                'label' => 'Custom ID',
                'help' => 'Unique identifier,leave empty for auto-generation',
                'attr' => [
                    'placeholder' => 'e.g. LAPTOP-001'
                ]
            ])
            ->add('version', HiddenType::class, [
                'mapped' => true,
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $item = $event->getData();
            $form = $event->getForm();

            // Если нет item или inventory - выходим
            if (!$item || !$item->getInventory()) {
                return;
            }

            $inventory = $item->getInventory();

            foreach ($inventory->getInventoryAttributes() as $attribute) {
                $existingValue = null;
                foreach ($item->getItemAttributeValues() as $attrValue) {
                    if ($attrValue->getAttribute()->getId() === $attribute->getId()) {
                        $existingValue = $attrValue->getValue();
                        break;
                    }
                }

                $fieldType = $this->getFieldTypeForAttribute($attribute->getType());

                $form->add('attr_' . $attribute->getId(), $fieldType, [
                    'label' => $attribute->getName(),
                    'required' => $attribute->isRequired(),
                    'mapped' => false,
                    'data' => $existingValue,
                ]);
            }
        });
    }

    private function getFieldTypeForAttribute(AttributeType $type): string
    {
        return match($type) {
            AttributeType::STRING => TextType::class,
            AttributeType::TEXT => TextareaType::class,
            AttributeType::INTEGER => NumberType::class,
            AttributeType::BOOLEAN => CheckboxType::class,
            default => TextType::class,
        };
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
