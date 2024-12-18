<?php


namespace App\Form;

use App\Entity\User;
use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, EmailType, PasswordType, DateType, ChoiceType, SubmitType};

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // User Fields
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('password', PasswordType::class, ['label' => 'Password'])
            ->add('role', HiddenType::class, [
                'data' => 'ROLE_PATIENT',
            ])
            // Patient Fields
            ->add('patient', PatientType::class, ['label' => false])
            ->add('submit', SubmitType::class, ['label' => 'Register']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class)
            ->add('last_name', TextType::class)
            ->add('gender', ChoiceType::class, [
                'choices' => ['Male' => 'Male', 'Female' => 'Female'],
                'required' => false
            ])
            ->add('date_of_birth', DateType::class, ['widget' => 'single_text'])
            ->add('phone_number', TextType::class, ['required' => false])
            ->add('chronic_disease', ChoiceType::class, [
                'choices' => ['Yes' => true, 'No' => false],
                'required' => false
            ])
            ->add('address', TextType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
