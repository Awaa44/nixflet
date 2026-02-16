<?php

namespace App\Form;

use App\Entity\Serie;
use Doctrine\ORM\Query\Expr\Select;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la serie',
                'required' => false,
            ])
            ->add('overview')
            ->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'En cours' => 'returning',
                    'Terminé' => 'ended',
                    'Annulé' => 'Canceled',
                ],
                'placeholder' => '--Choisissez un statut--',
            ])
            ->add('vote', TextType::class, [
                'required' => false,
            ])
            ->add('popularity')
            ->add('genres')
            ->add('firstAirDate', DateType::class, [
                'widget' => 'choice',
                'years' => range(1900, date('Y')),  // De 1900 à l'année actuelle
                'format' => 'dd-MM-yyyy',           // Ordre d'affichage : jour-mois-année
                'placeholder' => [
                    'year' => 'Année',
                    'month' => 'Mois',
                    'day' => 'Jour']
            ])
            ->add('lastAirDate')
            ->add('backdrop')
            ->add('poster')
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
        ]);
    }
}
