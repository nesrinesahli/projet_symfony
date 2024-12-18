<?php

namespace App\Form;

use App\Entity\Professionals;
use App\Entity\Rendezvous;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientRendezvousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Add the professional selection field
        $builder->add('professional', EntityType::class, [
            'class' => Professionals::class,
            'choice_label' => function ($professional) {
                return $professional->getFirstName() . ' ' . $professional->getLastName() . ' (' . $professional->getSpecialty() . ')';
            },
            'label' => 'Choisir un professionnel',
            'placeholder' => 'Sélectionnez un professionnel',
            'choices' => $options['professionals'], // Use the professionals option
        ]);

        $builder->add('appointmentDate', \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class, [
            'widget' => 'single_text',
            'label' => 'Date et heure',
        ]);

        $builder->add('notes', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class, [
            'required' => false,
            'label' => 'Pourquoi vous prenez rendez-vous ?',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rendezvous::class,
            'professionals' => [], // Define the 'professionals' option with a default value
        ]);
    }
}
