<?php

// src/Form/PatientFormType.php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', TextType::class, [
                'label' => 'First Name'
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Last Name'
            ])
            ->add('gender', TextType::class, [
                'label' => 'Gender',
                'required' => false
            ])
            ->add('date_of_birth', DateType::class, [
                'label' => 'Date of Birth',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('chronic_disease', CheckboxType::class, [
                'label' => 'Chronic Disease',
                'required' => false
            ])
            ->add('phone_number', TextType::class, [
                'label' => 'Phone Number'
            ])
            ->add('address', TextType::class, [
                'label' => 'Address',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
