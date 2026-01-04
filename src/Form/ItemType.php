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
                'label' => 'Custom ID',
                'help' => 'Unique identifier for this item',
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

            // Для каждого InventoryAttribute добавляем поле в форму
            foreach ($inventory->getInventoryAttributes() as $attribute) {
                // Найти существующее значение для этого attribute
                $existingValue = null;
                foreach ($item->getItemAttributeValues() as $attrValue) {
                    if ($attrValue->getAttribute()->getId() === $attribute->getId()) {
                        $existingValue = $attrValue->getValue();
                        break;
                    }
                }

                // Определить тип поля по типу атрибута
                $fieldType = $this->getFieldTypeForAttribute($attribute->getType());

                // Добавить поле в форму
                $form->add('attr_' . $attribute->getId(), $fieldType, [
                    'label' => $attribute->getName(),
                    'required' => $attribute->isRequired(),
                    'mapped' => false, // НЕ мапим напрямую на Item
                    'data' => $existingValue, // Подставляем существующее значение
                ]);
            }
        });
    }

    private function getFieldTypeForAttribute(AttributeType $type): string
    {
        return match($type) {
            AttributeType::STRING => TextType::class,        // Короткий текст
            AttributeType::TEXT => TextareaType::class,      // Длинный текст
            AttributeType::INTEGER => NumberType::class,     // Число
            AttributeType::BOOLEAN => CheckboxType::class,   // Чекбокс
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
