<?php

namespace App\Form;

use App\Entity\Rendezvous;
use App\Entity\Patient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RendezvousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'fullName', // Ensure this method exists in Patient
                'label' => 'Patient',
            ])
            ->add('appointmentDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Scheduled' => 'scheduled',
                    'Completed' => 'completed',
                    'Canceled' => 'canceled',
                ],
                'label' => 'Statut',
            ])
            ->add('notes', TextareaType::class, [
                'required' => false,
                'label' => 'Notes',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rendezvous::class,
        ]);
    }

}
