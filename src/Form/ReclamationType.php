<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextareaType::class, [
                'label' => 'nom',
                    'attr' => [ 
                                'placeholder' => 'Entrez votre Nom ici...',
                    ],] )
            ->add('prenom', TextareaType::class, [
                'label' => 'prenom',
                    'attr' => [ 
                                'placeholder' => 'Entrez votre Prenom ici...',
                    ],] )
            ->add('email', TextareaType::class, [
                'label' => 'email',
                    'attr' => [ 
                                'placeholder' => 'Entrez votre Email ici...',
                    ],] )
            ->add('tel', TextareaType::class, [
                'label' => 'tel',
                    'attr' => [ 
                                'placeholder' => 'Entrez votre Tel ici...',
                    ],] )
            // ->add('etat', TextareaType::class, [
            //     'label' => 'etat',
            //         'attr' => [ 
            //                     'placeholder' => 'Entrez votre Etat ici...',
                    // ],] )
            ->add('description', TextareaType::class, [
                'label' => 'description',
                    'attr' => [ 'rows' => 5, // nombre de lignes visibles
                                'placeholder' => 'Entrez votre description ici...',
                    ],] )
            ->add('date_reclamation')
            ->add('type')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
