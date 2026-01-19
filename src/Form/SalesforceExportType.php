<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

class SalesforceExportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      $builder
      ->add('company', TextType::class, [
        'label' => 'Company Name',
        'required' => true,
      ])
      ->add('phone', TelType::class,[
        'label' => 'Phone Number',
        'required' => true,
      ])
      ->add('website', UrlType::class,[
        'label' => 'Website',
        'required' => false,
      ])
      ->add('industry', ChoiceType::class,[
        'label' => 'Industry',
        'required' => false,
        'choices' => [
          'Technology' => 'Technology',
          'Manufacturing' => 'Manufacturing',
          'Retail' => 'Retail',
          'Healthcare' => 'Healthcare',
          'Finance' => 'Finance',
          'Other' => 'Other'
        ],
      ])
      ->add('jobTitle', TextType::class,[
        'label' => 'Job Title',
        'required' => false,
      ]);
    }
}








?>